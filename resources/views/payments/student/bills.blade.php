@extends('layouts.app')

@section('title', 'Tagihan & Pembayaran SPP')
@section('page_title', 'Tagihan & Pembayaran SPP')

@section('content')
@php
    $total_unpaid = $bills->whereIn('status', ['unpaid', 'pending'])->sum('amount');
    $total_paid   = $bills->where('status', 'paid')->sum('amount');
@endphp

{{-- Summary Cards --}}
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 1.75rem;">
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="bi bi-exclamation-circle-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">Rp {{ number_format($total_unpaid, 0, ',', '.') }}</div>
            <div class="stat-label">Total Tagihan Tertunggak</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-emerald"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">Rp {{ number_format($total_paid, 0, ',', '.') }}</div>
            <div class="stat-label">Total Telah Dibayar</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="bi bi-receipt-cutoff"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $bills->count() }}</div>
            <div class="stat-label">Total Tagihan</div>
        </div>
    </div>
</div>

{{-- Bills List --}}
<div class="card">
    <div class="card-header">
        <h3>🧾 Riwayat & Daftar Tagihan</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        @forelse($bills as $bill)
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <div style="width: 50px; height: 50px; border-radius: 12px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
                background: {{ $bill->status === 'paid' ? '#d1fae5' : ($bill->status === 'pending' ? '#fef3c7' : '#fee2e2') }};">
                {{ $bill->status === 'paid' ? '✅' : ($bill->status === 'pending' ? '⏳' : '📋') }}
            </div>
            <div style="flex: 1; min-width: 200px;">
                <div style="font-weight: 700; font-size: 0.9rem; color: #1e293b;">{{ $bill->title }}</div>
                <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 2px;">
                    Kode: {{ $bill->bill_code }} ·
                    Jatuh tempo: {{ $bill->due_date->isoFormat('D MMMM YYYY') }}
                </div>
            </div>
            <div style="text-align: right; min-width: 150px;">
                <div style="font-size: 1.1rem; font-weight: 800; color: {{ $bill->status === 'paid' ? '#047857' : '#1e293b' }};">
                    Rp {{ number_format($bill->amount, 0, ',', '.') }}
                </div>
                <span class="badge badge-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'pending' ? 'warning' : 'danger') }}">
                    {{ $bill->status === 'paid' ? 'Lunas' : ($bill->status === 'pending' ? 'Menunggu Verifikasi' : 'Belum Bayar') }}
                </span>
            </div>
            <div style="min-width: 140px; display: flex; gap: 0.4rem; flex-wrap: wrap; justify-content: flex-end;">
                @if($bill->status === 'paid')
                    @php $pmt = $bill->successfulPayment(); @endphp
                    @if($pmt)
                    <a href="{{ route('payment.student.receipt', $pmt->id) }}" class="btn btn-outline btn-sm" target="_blank">
                        <i class="bi bi-download"></i> Kwitansi
                    </a>
                    @endif
                @elseif($bill->status === 'pending')
                    <span style="font-size: 0.75rem; color: #d97706; display: flex; align-items: center; gap: 0.3rem;">
                        <i class="bi bi-hourglass-split"></i> Sedang Diverifikasi
                    </span>
                @else
                    <a href="{{ route('payment.student.pay', $bill->id) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-credit-card"></i> Bayar Sekarang
                    </a>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 4rem 2rem; color: #94a3b8;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🎉</div>
            <p style="font-weight: 600;">Tidak ada tagihan yang ditemukan!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
