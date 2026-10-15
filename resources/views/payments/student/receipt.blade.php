@extends('layouts.app')

@section('title', 'Kwitansi Pembayaran')
@section('page_title', 'Kwitansi Pembayaran Resmi')

@section('head')
<style>
    @media print {
        .sidebar, .topbar, .no-print { display: none !important; }
        .main-content { margin-left: 0 !important; }
        .page-content { padding: 0 !important; }
        body { background: white !important; }
        .receipt-card { box-shadow: none !important; }
    }
</style>
@endsection

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem;" class="no-print">
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer-fill"></i> Cetak Kwitansi</button>
        <a href="{{ route('payment.student.bills') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="receipt-card" style="background: white; border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 8px 30px rgba(0,0,0,0.08);">
        {{-- Header --}}
        <div style="background: linear-gradient(135deg, #064e3b, #047857); padding: 2rem; text-align: center; color: white;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">🕌</div>
            <div style="font-size: 1.2rem; font-weight: 800; margin-bottom: 0.15rem;">MADRASAH ALIYAH NEGERI PANDANARAN</div>
            <div style="font-size: 0.78rem; color: rgba(255,255,255,0.8);">Jl. Kaliurang KM 14.5, Ngemplak, Sleman, D.I. Yogyakarta 55584</div>
            <div style="font-size: 0.75rem; color: rgba(255,255,255,0.7);">Telp: (0274) 895-7721 | Email: info@manpandanaran.sch.id</div>
            <div style="background: rgba(251,191,36,0.2); border: 1px solid rgba(251,191,36,0.4); border-radius: 8px; display: inline-block; padding: 0.3rem 1.25rem; margin-top: 1rem; color: #fbbf24; font-weight: 700; font-size: 0.85rem; letter-spacing: 2px;">
                KWITANSI PEMBAYARAN RESMI
            </div>
        </div>

        {{-- Body --}}
        <div style="padding: 2rem;">
            {{-- Payment Code & Date --}}
            <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.82rem;">
                <div>
                    <div style="color: #94a3b8;">No. Kwitansi</div>
                    <div style="font-weight: 800; font-size: 1rem; color: #047857;">{{ $payment->payment_code }}</div>
                </div>
                <div style="text-align: right;">
                    <div style="color: #94a3b8;">Tanggal Verifikasi</div>
                    <div style="font-weight: 700;">{{ $payment->verified_at?->isoFormat('D MMMM YYYY') }}</div>
                </div>
            </div>

            <div style="height: 1px; background: linear-gradient(to right, #047857, #e2e8f0); margin-bottom: 1.5rem;"></div>

            {{-- Student Info --}}
            <div style="background: #f8fafc; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 0.75rem;">Identitas Pembayar</div>
                @php
                    $student = $payment->bill->student;
                    $details = [
                        'Nama Siswa'   => $student->name,
                        'NISN'         => $student->identity_number ?? '-',
                        'Kelas'        => $student->schoolClass?->name ?? '-',
                        'Tahun Ajaran' => $student->schoolClass?->academic_year ?? '2025/2026',
                    ];
                @endphp
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem;">
                    @foreach($details as $key => $val)
                    <div>
                        <div style="font-size: 0.72rem; color: #94a3b8;">{{ $key }}</div>
                        <div style="font-weight: 700; font-size: 0.875rem; color: #1e293b;">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Payment Info --}}
            <div style="margin-bottom: 1.5rem;">
                <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 0.75rem;">Rincian Pembayaran</div>
                @php
                    $bill = $payment->bill;
                    $payRows = [
                        ['Jenis Pembayaran', $bill->feeType->name],
                        ['Keterangan', $bill->title],
                        ['Metode Pembayaran', strtoupper(str_replace('_', ' ', $payment->payment_method))],
                        ['Diverifikasi oleh', $payment->verifier?->name ?? 'Sistem Otomatis'],
                    ];
                @endphp
                @foreach($payRows as [$label, $val])
                <div style="display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px dashed #e2e8f0; font-size: 0.875rem;">
                    <span style="color: #64748b;">{{ $label }}</span>
                    <span style="font-weight: 600; color: #1e293b;">{{ $val }}</span>
                </div>
                @endforeach
            </div>

            {{-- Amount --}}
            <div style="background: linear-gradient(135deg, #064e3b, #047857); border-radius: 14px; padding: 1.5rem; text-align: center; margin-bottom: 1.5rem; color: white;">
                <div style="font-size: 0.82rem; color: rgba(255,255,255,0.7);">Total Pembayaran Diterima</div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #fbbf24; margin: 0.25rem 0;">
                    Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}
                </div>
                <div style="font-size: 0.75rem; color: rgba(255,255,255,0.65);">{{ ucwords(terbilang($payment->amount_paid)) }} Rupiah</div>
            </div>

            {{-- QR & Signature --}}
            <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div style="text-align: center;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #064e3b, #047857); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.4rem;">
                        <div style="color: white; font-size: 1.8rem;">📱</div>
                    </div>
                    <div style="font-size: 0.68rem; color: #94a3b8;">Scan untuk verifikasi</div>
                    <div style="font-size: 0.65rem; color: #cbd5e1; font-family: monospace; margin-top: 2px;">{{ Str::limit($payment->payment_code, 20) }}</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 0.78rem; color: #64748b; margin-bottom: 1.5rem;">Sleman, {{ $payment->verified_at?->isoFormat('D MMMM YYYY') }}</div>
                    <div style="font-size: 0.78rem; color: #64748b;">Bendahara MAN Pandanaran,</div>
                    <div style="margin: 2rem 0 0.25rem; font-weight: 800; font-size: 0.875rem; color: #1e293b; border-top: 2px solid #1e293b; padding-top: 0.35rem;">
                        {{ $payment->verifier?->name ?? 'Sistem Otomatis' }}
                    </div>
                    <div style="font-size: 0.7rem; color: #94a3b8;">NIP. {{ $payment->verifier?->identity_number ?? '-' }}</div>
                </div>
            </div>

            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 0.75rem 1rem; margin-top: 1.5rem; font-size: 0.72rem; color: #166534; text-align: center;">
                ✅ Kwitansi ini sah secara digital dan dapat diverifikasi keabsahannya melalui sistem informasi MAN Pandanaran.
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function terbilang(n) {
    // Simple helper - actual terbilang handled server-side below
    return '';
}
</script>
@endsection
