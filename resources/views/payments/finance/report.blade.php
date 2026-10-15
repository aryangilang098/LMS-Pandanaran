@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('page_title', 'Laporan Keuangan Madrasah')

@section('content')
{{-- Filters --}}
<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form method="GET" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <select name="month" class="form-input" style="width: auto;">
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                <option value="{{ $i+1 }}" {{ $month == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                @endforeach
            </select>
            <select name="year" class="form-input" style="width: auto;">
                @for($y = 2024; $y <= now()->year + 1; $y++)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel-fill"></i> Filter</button>
            <button type="button" onclick="window.print()" class="btn btn-outline btn-sm" style="margin-left: auto;">
                <i class="bi bi-printer-fill"></i> Cetak Laporan
            </button>
        </form>
    </div>
</div>

{{-- Summary --}}
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 1.75rem;">
    <div class="stat-card">
        <div class="stat-icon si-emerald"><i class="bi bi-cash-stack"></i></div>
        <div class="stat-body">
            <div class="stat-num" style="font-size: 1.3rem;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pemasukan Periode Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="bi bi-receipt"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $payments->count() }}</div>
            <div class="stat-label">Jumlah Transaksi Lunas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="bi bi-calculator-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num" style="font-size: 1.3rem;">Rp {{ $payments->count() > 0 ? number_format($totalIncome / $payments->count(), 0, ',', '.') : 0 }}</div>
            <div class="stat-label">Rata-rata per Transaksi</div>
        </div>
    </div>
</div>

{{-- Breakdown by Fee Type --}}
@if($byFeeType->count())
<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-header"><h3>📊 Rincian per Jenis Biaya</h3></div>
    <div class="card-body" style="padding: 0;">
        <table>
            <thead><tr><th>Jenis Biaya</th><th>Jumlah Transaksi</th><th>Total Nominal</th><th>Persentase</th></tr></thead>
            <tbody>
                @foreach($byFeeType as $feeTypeName => $group)
                @php $subtotal = $group->sum('amount_paid'); $pct = $totalIncome > 0 ? round(($subtotal / $totalIncome) * 100) : 0; @endphp
                <tr>
                    <td style="font-weight: 700;">{{ $feeTypeName }}</td>
                    <td>{{ $group->count() }} transaksi</td>
                    <td style="font-weight: 800; color: #047857;">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="height: 8px; background: #f1f5f9; border-radius: 50px; width: 100px; overflow: hidden;">
                                <div style="height: 100%; width: {{ $pct }}%; background: linear-gradient(90deg, #047857, #10b981); border-radius: 50px;"></div>
                            </div>
                            <span style="font-size: 0.78rem; font-weight: 700;">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Detail Transactions --}}
<div class="card">
    <div class="card-header">
        <h3>📋 Detail Transaksi Berhasil</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>#</th><th>Kode</th><th>Siswa</th><th>Jenis Biaya</th><th>Metode</th><th>Tanggal Verifikasi</th><th>Nominal</th></tr>
                </thead>
                <tbody>
                    @forelse($payments as $i => $p)
                    <tr>
                        <td style="color: #94a3b8;">{{ $i + 1 }}</td>
                        <td style="font-size: 0.72rem; font-family: monospace; color: #94a3b8;">{{ $p->payment_code }}</td>
                        <td>
                            <div style="font-weight: 700;">{{ $p->bill->student->name }}</div>
                            <div style="font-size: 0.72rem; color: #94a3b8;">{{ $p->bill->student->schoolClass?->name }}</div>
                        </td>
                        <td>{{ $p->bill->feeType->name }}</td>
                        <td><span class="badge badge-info">{{ str_replace('_',' ',strtoupper($p->payment_method)) }}</span></td>
                        <td style="font-size: 0.78rem; color: #64748b;">{{ $p->verified_at?->isoFormat('D MMM YYYY HH:mm') }}</td>
                        <td style="font-weight: 800; color: #047857; white-space: nowrap;">Rp {{ number_format($p->amount_paid, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align: center; padding: 3rem; color: #94a3b8;">Tidak ada data transaksi pada periode ini.</td></tr>
                    @endforelse
                </tbody>
                @if($payments->count())
                <tfoot>
                    <tr style="background: #f8fafc;">
                        <td colspan="6" style="font-weight: 800; text-align: right; padding: 0.875rem 1rem;">TOTAL:</td>
                        <td style="font-weight: 800; color: #047857; font-size: 1rem; padding: 0.875rem 1rem;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
