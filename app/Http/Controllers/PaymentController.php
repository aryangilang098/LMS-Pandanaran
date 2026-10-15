<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentBill;
use App\Models\Payment;
use App\Models\FeeType;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // ─── SISWA/WALI ──────────────────────────────────────────────────────────────

    public function studentBills()
    {
        $user = Auth::user();
        $bills = StudentBill::with(['feeType', 'payments'])
            ->where('student_id', $user->id)
            ->latest()
            ->get();

        return view('payments.student.bills', compact('bills', 'user'));
    }

    public function showPayForm($billId)
    {
        $user = Auth::user();
        $bill = StudentBill::with(['feeType', 'student', 'payments'])
            ->where('student_id', $user->id)
            ->findOrFail($billId);

        if ($bill->status === 'paid') {
            return redirect()->route('payment.student.bills')
                ->with('info', 'Tagihan ini sudah lunas.');
        }

        // Cari pembayaran pending teranyar atau buat baru
        $payment = Payment::where('bill_id', $bill->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        $snapToken = $payment?->snap_token;
        $orderId = $payment?->payment_code ?? ('PAY-' . $bill->id . '-' . time());

        // Inisialisasi Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

        if (!$snapToken) {
            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $bill->amount,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email'      => $user->email,
                    'phone'      => $user->phone ?? '081234567890',
                ],
                'item_details' => [
                    [
                        'id'       => $bill->bill_code,
                        'price'    => (int) $bill->amount,
                        'quantity' => 1,
                        'name'     => substr($bill->title, 0, 50),
                    ]
                ],
                'enabled_payments' => [
                    'qris', 'gopay', 'shopeepay',
                    'bca_va', 'bri_va', 'bni_va', 'mandiri_va', 'permata_va', 'cimb_va'
                ]
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
            } catch (\Exception $e) {
                // Fallback token jika demo key belum valid di Midtrans dashboard
                $snapToken = 'DEMO-SNAP-TOKEN-' . Str::random(16);
            }

            if (!$payment) {
                $payment = Payment::create([
                    'payment_code'   => $orderId,
                    'bill_id'        => $bill->id,
                    'amount_paid'    => $bill->amount,
                    'payment_method' => 'midtrans_va_qris',
                    'snap_token'     => $snapToken,
                    'status'         => 'pending',
                    'paid_at'        => now(),
                ]);
            } else {
                $payment->update([
                    'snap_token' => $snapToken,
                ]);
            }
        }

        $clientKey = config('services.midtrans.client_key');

        return view('payments.student.pay', compact('bill', 'user', 'payment', 'snapToken', 'clientKey'));
    }

    public function processPayment(Request $request, $billId)
    {
        $user = Auth::user();
        $bill = StudentBill::where('student_id', $user->id)->findOrFail($billId);

        if ($bill->status === 'paid') {
            return redirect()->route('payment.student.bills')->with('info', 'Tagihan sudah lunas.');
        }

        $payment = Payment::where('bill_id', $bill->id)->latest()->first();

        if ($request->has('result_data')) {
            $result = json_decode($request->result_data, true);
            $paymentType = $result['payment_type'] ?? 'midtrans';
            $transactionId = $result['transaction_id'] ?? null;
            $transactionStatus = $result['transaction_status'] ?? 'pending';

            if ($payment) {
                $statusMap = [
                    'settlement' => 'success',
                    'capture'    => 'success',
                    'pending'    => 'pending',
                    'deny'       => 'rejected',
                    'expire'     => 'rejected',
                    'cancel'     => 'rejected',
                ];

                $newStatus = $statusMap[$transactionStatus] ?? 'pending';

                $payment->update([
                    'payment_method'          => $paymentType,
                    'midtrans_transaction_id' => $transactionId,
                    'payment_type'            => $paymentType,
                    'status'                  => $newStatus,
                    'verified_at'             => $newStatus === 'success' ? now() : null,
                ]);

                if ($newStatus === 'success') {
                    $bill->update(['status' => 'paid']);
                } else {
                    $bill->update(['status' => 'pending']);
                }

                return redirect()->route('payment.student.confirmation', $payment->id)
                    ->with('success', 'Status pembayaran berhasil diperbarui!');
            }
        }

        return redirect()->route('payment.student.bills');
    }

    /**
     * Webhook Callback Notification dari Midtrans
     */
    public function midtransNotification(Request $request)
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

        try {
            $notif = new \Midtrans\Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            $payment = Payment::where('payment_code', $orderId)->first();

            if (!$payment) {
                return response()->json(['message' => 'Payment record not found'], 404);
            }

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $payment->update(['status' => 'pending']);
                    } else {
                        $payment->update(['status' => 'success', 'verified_at' => now()]);
                        $payment->bill->update(['status' => 'paid']);
                    }
                }
            } else if ($transaction == 'settlement') {
                $payment->update([
                    'status'                  => 'success',
                    'payment_type'            => $type,
                    'midtrans_transaction_id' => $notif->transaction_id ?? null,
                    'verified_at'             => now(),
                ]);
                $payment->bill->update(['status' => 'paid']);
            } else if ($transaction == 'pending') {
                $payment->update([
                    'status'                  => 'pending',
                    'payment_type'            => $type,
                    'midtrans_transaction_id' => $notif->transaction_id ?? null,
                ]);
                $payment->bill->update(['status' => 'pending']);
            } else if (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                $payment->update([
                    'status'                  => 'rejected',
                    'payment_type'            => $type,
                    'midtrans_transaction_id' => $notif->transaction_id ?? null,
                ]);
                $payment->bill->update(['status' => 'unpaid']);
            }

            return response()->json(['message' => 'Notification processed successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirmation($paymentId)
    {
        $user = Auth::user();
        $payment = Payment::with(['bill.feeType', 'bill.student'])->findOrFail($paymentId);
        return view('payments.student.confirmation', compact('payment', 'user'));
    }

    public function receipt($paymentId)
    {
        $payment = Payment::with(['bill.student.schoolClass', 'bill.feeType', 'verifier'])->findOrFail($paymentId);

        if ($payment->status !== 'success') {
            return back()->with('error', 'Kwitansi hanya tersedia untuk pembayaran yang telah diverifikasi.');
        }

        return view('payments.student.receipt', compact('payment'));
    }

    // ─── BENDAHARA ───────────────────────────────────────────────────────────────

    public function financeIndex(Request $request)
    {
        $query = Payment::with(['bill.student', 'bill.feeType', 'verifier']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(20);

        $stats = [
            'total_pending' => Payment::where('status', 'pending')->count(),
            'total_success' => Payment::where('status', 'success')->sum('amount_paid'),
            'this_month'    => Payment::where('status', 'success')
                ->whereMonth('verified_at', now()->month)->sum('amount_paid'),
        ];

        return view('payments.finance.index', compact('payments', 'stats'));
    }

    public function verifyPayment(Request $request, $paymentId)
    {
        $payment = Payment::with('bill')->findOrFail($paymentId);

        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($request->action === 'approve') {
            $payment->update([
                'status'      => 'success',
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);
            $payment->bill->update(['status' => 'paid']);

            return back()->with('success', "Pembayaran {$payment->payment_code} berhasil diverifikasi!");
        } else {
            $payment->update([
                'status'      => 'rejected',
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);
            $payment->bill->update(['status' => 'unpaid']);

            return back()->with('error', "Pembayaran {$payment->payment_code} telah ditolak.");
        }
    }

    public function generateBills(Request $request)
    {
        $classes = SchoolClass::all();
        $feeTypes = FeeType::all();
        return view('payments.finance.generate_bills', compact('classes', 'feeTypes'));
    }

    public function storeBills(Request $request)
    {
        $request->validate([
            'class_id'    => 'required|exists:classes,id',
            'fee_type_id' => 'required|exists:fee_types,id',
            'month'       => 'nullable|integer|min:1|max:12',
            'year'        => 'required|integer|min:2020',
            'amount'      => 'required|numeric|min:1000',
            'due_date'    => 'required|date',
        ]);

        $feeType = FeeType::findOrFail($request->fee_type_id);
        $students = User::where('role', 'siswa')
            ->where('class_id', $request->class_id)
            ->get();

        $count = 0;
        foreach ($students as $student) {
            $existing = StudentBill::where('student_id', $student->id)
                ->where('fee_type_id', $request->fee_type_id)
                ->when($request->month, fn($q) => $q->where('month', $request->month))
                ->where('year', $request->year)
                ->first();

            if (!$existing) {
                $billCode = 'INV-' . $request->year . str_pad($request->month ?? 0, 2, '0', STR_PAD_LEFT) . '-' . strtoupper(Str::random(6));
                $monthName = $request->month ? \Carbon\Carbon::create()->month($request->month)->isoFormat('MMMM') : '';
                StudentBill::create([
                    'bill_code'   => $billCode,
                    'student_id'  => $student->id,
                    'fee_type_id' => $request->fee_type_id,
                    'title'       => $feeType->name . ($monthName ? " - {$monthName} {$request->year}" : " {$request->year}"),
                    'month'       => $request->month,
                    'year'        => $request->year,
                    'amount'      => $request->amount,
                    'due_date'    => $request->due_date,
                    'status'      => 'unpaid',
                ]);
                $count++;
            }
        }

        return back()->with('success', "Berhasil membuat {$count} tagihan untuk siswa kelas yang dipilih.");
    }

    public function financeReport(Request $request)
    {
        $year  = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        $payments = Payment::with(['bill.student', 'bill.feeType'])
            ->where('status', 'success')
            ->whereYear('verified_at', $year)
            ->when($month, fn($q) => $q->whereMonth('verified_at', $month))
            ->latest('verified_at')
            ->get();

        $totalIncome = $payments->sum('amount_paid');
        $byFeeType = $payments->groupBy('bill.feeType.name');

        return view('payments.finance.report', compact('payments', 'totalIncome', 'byFeeType', 'year', 'month'));
    }
}
