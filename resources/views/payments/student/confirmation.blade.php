@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')
@section('page_title', 'Konfirmasi Pembayaran')

@section('content')
<div style="max-width: 640px; margin: 0 auto; text-align: center;">

    {{-- Success Animation --}}
    <div style="background: linear-gradient(135deg, #064e3b, #047857); border-radius: 24px; padding: 3rem 2rem; margin-bottom: 1.75rem; color: white;">
        <div style="font-size: 5rem; margin-bottom: 1rem; animation: bounce 1s ease infinite;">
            @if($payment->payment_method === 'qris') 📱
            @elseif(str_contains($payment->payment_method, '_va')) 🏦
            @else 📋
            @endif
        </div>
        <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem;">Pembayaran Berhasil Diajukan!</h2>
        <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem;">Menunggu verifikasi dari bendahara madrasah (1×24 jam)</p>
    </div>

    <style>
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
    </style>

    {{-- Payment Detail --}}
    <div class="card" style="margin-bottom: 1.25rem;">
        <div class="card-header">
            <h3>📄 Detail Transaksi</h3>
        </div>
        <div class="card-body">
            @php
                $rows = [
                    ['Kode Pembayaran', $payment->payment_code],
                    ['Nama Siswa', $payment->bill->student->name],
                    ['Tagihan', $payment->bill->title],
                    ['Jumlah Dibayar', 'Rp ' . number_format($payment->amount_paid, 0, ',', '.')],
                    ['Metode', strtoupper(str_replace('_', ' ', $payment->payment_method))],
                    ['Waktu', $payment->paid_at?->isoFormat('D MMMM YYYY, HH:mm') . ' WIB'],
                    ['Status', '⏳ Menunggu Verifikasi'],
                ];
                if ($payment->va_number)
                    $rows[] = ['Nomor VA / QRIS', $payment->va_number];
            @endphp
            @foreach($rows as [$label, $val])
            <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; font-size: 0.875rem;">
                <span style="color: #64748b;">{{ $label }}</span>
                <span style="font-weight: 700; color: #1e293b; text-align: right; max-width: 55%;">{{ $val }}</span>
            </div>
            @endforeach
        </div>
    </div>

    @if($payment->va_number && str_contains($payment->payment_method, '_va'))
    {{-- VA Number Display --}}
    <div style="background: #eff6ff; border: 2px solid #bfdbfe; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.25rem;">
        <div style="font-size: 0.8rem; color: #1d4ed8; font-weight: 700; margin-bottom: 0.5rem;">Nomor Virtual Account Anda</div>
        <div style="font-size: 1.8rem; font-weight: 800; letter-spacing: 4px; color: #1d4ed8; font-family: monospace;">
            {{ $payment->va_number }}
        </div>
        <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.5rem;">Transfer tepat sejumlah <strong>Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</strong> ke nomor di atas.</div>
    </div>
    @endif

    {{-- Steps --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header"><h3>📋 Langkah Selanjutnya</h3></div>
        <div class="card-body">
            @php $steps = [
                $payment->payment_method === 'manual_transfer'
                    ? ['📋', 'Transfer ke Rekening Madrasah', 'Lakukan transfer ke rekening BSI/BNI Syariah yang tertera sesuai nominal tagihan.']
                    : ($payment->va_number && str_contains($payment->payment_method, '_va')
                        ? ['🏦', 'Transfer ke Nomor VA', 'Transfer tepat sejumlah tagihan ke nomor Virtual Account yang tertera di atas.']
                        : ['📱', 'Scan QRIS', 'Buka aplikasi pembayaran dan scan QRIS yang diterima untuk menyelesaikan pembayaran.']),
                ['⏳', 'Menunggu Verifikasi Bendahara', 'Bendahara madrasah akan memverifikasi pembayaran Anda dalam 1×24 jam kerja.'],
                ['✅', 'Unduh Kwitansi Resmi', 'Setelah terverifikasi, unduh kwitansi resmi digital dengan QR Code keabsahan.'],
            ]; @endphp
            @foreach($steps as $i => [$icon, $title, $desc])
            <div style="display: flex; gap: 1rem; padding: 0.875rem 0; border-bottom: 1px solid #f1f5f9; {{ $loop->last ? 'border: none;' : '' }}">
                <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #064e3b, #047857); border-radius: 9px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 0.8rem;">{{ $i+1 }}</div>
                <div style="text-align: left;">
                    <div style="font-weight: 700; font-size: 0.875rem; color: #1e293b;">{{ $icon }} {{ $title }}</div>
                    <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px; line-height: 1.5;">{{ $desc }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <a href="{{ route('payment.student.bills') }}" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.875rem; font-size: 1rem;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Tagihan
    </a>
</div>
@endsection
