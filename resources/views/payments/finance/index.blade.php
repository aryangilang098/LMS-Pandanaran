@extends('layouts.app')

@section('title', 'Manajemen Pembayaran')
@section('page_title', 'Verifikasi Pembayaran Masuk')

@section('content')

{{-- Verification Popup Modal --}}
<div id="verifyModal" style="display:none; position:fixed; inset:0; z-index:9999; padding:1.5rem;">
    {{-- Backdrop --}}
    <div id="verifyBackdrop" onclick="closeVerifyModal()" style="
        position:absolute; inset:0;
        background: rgba(2,44,34,0.65);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        animation: fadeIn 0.25s ease;
    "></div>

    {{-- Card --}}
    <div id="verifyCard" style="
        position:relative; z-index:2;
        max-width: 480px; width: 100%;
        margin: auto; top: 50%; transform: translateY(-50%);
        background: white;
        border-radius: 24px;
        box-shadow: 0 32px 80px rgba(0,0,0,0.25), 0 0 0 1px rgba(0,0,0,0.05);
        overflow: hidden;
        animation: slideUp 0.3s cubic-bezier(0.34,1.56,0.64,1);
    ">
        {{-- Top gradient header --}}
        <div id="verifyHeader" style="padding: 2rem 2rem 1.5rem; text-align: center; position: relative;">
            {{-- Close button --}}
            <button onclick="closeVerifyModal()" style="
                position:absolute; top:1rem; right:1rem;
                width:32px; height:32px; border-radius:50%; border:none;
                background: rgba(255,255,255,0.2); color:white;
                cursor:pointer; font-size:1rem; display:flex; align-items:center; justify-content:center;
                transition: background 0.2s;
            " onmouseover="this.style.background='rgba(255,255,255,0.35)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="bi bi-x-lg"></i>
            </button>

            <div id="verifyIcon" style="
                width: 72px; height: 72px; border-radius: 20px;
                margin: 0 auto 1rem;
                display: flex; align-items: center; justify-content: center;
                font-size: 2rem;
                box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            "></div>
            <h2 id="verifyTitle" style="color:white; font-size: 1.3rem; font-weight: 800; margin-bottom: 0.35rem;"></h2>
            <p id="verifySubtitle" style="color: rgba(255,255,255,0.8); font-size: 0.85rem;"></p>
        </div>

        {{-- Body --}}
        <div style="padding: 1.5rem 2rem;">
            {{-- Info rows --}}
            <div id="verifyInfo" style="
                background: #f8fafc; border-radius: 14px; padding: 1rem 1.25rem;
                margin-bottom: 1.25rem; border: 1px solid #e2e8f0;
            "></div>

            {{-- Warning box --}}
            <div id="verifyWarning" style="
                border-radius: 12px; padding: 0.875rem 1.1rem;
                margin-bottom: 1.5rem; display: flex; gap: 0.75rem; align-items: flex-start;
                font-size: 0.82rem;
            "></div>

            {{-- Action buttons --}}
            <div style="display: flex; gap: 0.75rem;">
                <button onclick="closeVerifyModal()" style="
                    flex: 1; padding: 0.875rem; border-radius: 12px; border: 1.5px solid #e2e8f0;
                    background: white; color: #64748b; font-weight: 600; font-size: 0.9rem;
                    cursor: pointer; transition: all 0.2s; font-family: inherit;
                " onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                    <i class="bi bi-x"></i> Batal
                </button>
                <button id="verifyConfirmBtn" style="
                    flex: 2; padding: 0.875rem; border-radius: 12px; border: none;
                    color: white; font-weight: 700; font-size: 0.95rem;
                    cursor: pointer; transition: all 0.2s; font-family: inherit;
                    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
                "></button>
            </div>
        </div>
    </div>

    {{-- Hidden forms --}}
    <form id="approveForm" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="approve">
    </form>
    <form id="rejectForm" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="reject">
    </form>
</div>

{{-- Stats --}}
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 1.75rem;">
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $stats['total_pending'] }}</div>
            <div class="stat-label">Menunggu Verifikasi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-emerald"><i class="bi bi-graph-up-arrow"></i></div>
        <div class="stat-body">
            <div class="stat-num">Rp {{ number_format($stats['total_success'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Pemasukan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="bi bi-calendar-month"></i></div>
        <div class="stat-body">
            <div class="stat-num">Rp {{ number_format($stats['this_month'], 0, ',', '.') }}</div>
            <div class="stat-label">Pemasukan Bulan Ini</div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form method="GET" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <select name="status" class="form-input" style="width: auto; padding: 0.5rem 0.875rem;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>⏳ Pending</option>
                <option value="success"  {{ request('status') === 'success'  ? 'selected' : '' }}>✅ Sukses</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
            </select>
            <a href="{{ route('payment.finance.index') }}" class="btn btn-outline btn-sm">Reset</a>
            <a href="{{ route('payment.finance.report') }}" class="btn btn-gold btn-sm" style="margin-left: auto;"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
            <a href="{{ route('payment.finance.generate') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Generate Tagihan</a>
        </form>
    </div>
</div>

{{-- Payments Table --}}
<div class="card">
    <div class="card-header">
        <h3>💳 Daftar Pembayaran ({{ $payments->total() }} data)</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Siswa</th>
                        <th>Tagihan</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Waktu Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                    <tr>
                        <td style="font-size: 0.72rem; color: #94a3b8; font-family: monospace;">{{ $p->payment_code }}</td>
                        <td>
                            <div style="font-weight: 700;">{{ $p->bill->student->name }}</div>
                            <div style="font-size: 0.72rem; color: #94a3b8;">{{ $p->bill->student->schoolClass?->name }}</div>
                        </td>
                        <td style="font-size: 0.82rem;">{{ Str::limit($p->bill->title, 30) }}</td>
                        <td style="font-weight: 800; color: #047857; white-space: nowrap;">Rp {{ number_format($p->amount_paid, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-info">{{ str_replace('_', ' ', strtoupper($p->payment_method)) }}</span>
                        </td>
                        <td style="font-size: 0.78rem; color: #64748b; white-space: nowrap;">{{ $p->paid_at?->isoFormat('D MMM YYYY HH:mm') }}</td>
                        <td>
                            <span class="badge badge-{{ $p->status === 'success' ? 'success' : ($p->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ $p->status === 'success' ? '✅ Sukses' : ($p->status === 'rejected' ? '❌ Ditolak' : '⏳ Pending') }}
                            </span>
                        </td>
                        <td>
                            @if($p->status === 'pending')
                            <div style="display: flex; gap: 0.35rem;">
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="openVerifyModal('approve', '{{ $p->id }}', '{{ $p->payment_code }}', '{{ addslashes($p->bill->student->name) }}', '{{ addslashes($p->bill->student->schoolClass?->name ?? '-') }}', '{{ addslashes($p->bill->title) }}', 'Rp {{ number_format($p->amount_paid, 0, ',', '.') }}', '{{ str_replace('_', ' ', strtoupper($p->payment_method)) }}', '{{ $p->paid_at?->isoFormat('D MMM YYYY HH:mm') }}', '{{ $p->proof_file ? asset('storage/' . $p->proof_file) : '' }}')">
                                    <i class="bi bi-check-lg"></i> Setujui
                                </button>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="openVerifyModal('reject', '{{ $p->id }}', '{{ $p->payment_code }}', '{{ addslashes($p->bill->student->name) }}', '{{ addslashes($p->bill->student->schoolClass?->name ?? '-') }}', '{{ addslashes($p->bill->title) }}', 'Rp {{ number_format($p->amount_paid, 0, ',', '.') }}', '{{ str_replace('_', ' ', strtoupper($p->payment_method)) }}', '{{ $p->paid_at?->isoFormat('D MMM YYYY HH:mm') }}', '{{ $p->proof_file ? asset('storage/' . $p->proof_file) : '' }}')">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                            </div>
                            @elseif($p->status === 'success')
                            <a href="{{ route('payment.student.receipt', $p->id) }}" class="btn btn-outline btn-sm" target="_blank">
                                <i class="bi bi-receipt"></i> Kwitansi
                            </a>
                            @else
                            <span style="font-size: 0.75rem; color: #dc2626;">Ditolak oleh {{ $p->verifier?->name }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem; color: #94a3b8;">
                            <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">✅</div>
                            Tidak ada pembayaran ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
        <div style="padding: 1rem 1.5rem; display: flex; justify-content: center;">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    @keyframes fadeIn  { from { opacity:0; } to { opacity:1; } }
    @keyframes slideUp { from { opacity:0; transform: translateY(calc(-50% + 40px)); } to { opacity:1; transform: translateY(-50%); } }

    #verifyConfirmBtn:hover { filter: brightness(1.08); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(0,0,0,0.2); }
</style>

<script>
const approveRoutes = @json($payments->mapWithKeys(fn($p) => [$p->id => route('payment.finance.verify', $p->id)]));

function openVerifyModal(type, id, code, student, cls, bill, amount, method, paidAt, proofUrl) {
    const modal   = document.getElementById('verifyModal');
    const header  = document.getElementById('verifyHeader');
    const icon    = document.getElementById('verifyIcon');
    const title   = document.getElementById('verifyTitle');
    const subtitle= document.getElementById('verifySubtitle');
    const info    = document.getElementById('verifyInfo');
    const warning = document.getElementById('verifyWarning');
    const btn     = document.getElementById('verifyConfirmBtn');

    const isApprove = type === 'approve';

    // ── Header gradient
    header.style.background = isApprove
        ? 'linear-gradient(135deg, #064e3b 0%, #059669 100%)'
        : 'linear-gradient(135deg, #7f1d1d 0%, #dc2626 100%)';

    // ── Icon
    icon.style.background = 'rgba(255,255,255,0.2)';
    icon.innerHTML = isApprove
        ? '<i class="bi bi-shield-check" style="color:white;font-size:2rem;"></i>'
        : '<i class="bi bi-shield-x" style="color:white;font-size:2rem;"></i>';

    // ── Title & subtitle
    title.textContent   = isApprove ? 'Konfirmasi Verifikasi' : 'Konfirmasi Penolakan';
    subtitle.textContent= isApprove
        ? 'Pastikan bukti transfer sudah valid sebelum menyetujui.'
        : 'Pembayaran akan ditolak dan siswa perlu mengajukan ulang.';

    // ── Info rows
    const rows = [
        ['<i class="bi bi-hash"></i> Kode Transaksi', `<code style="background:#e2e8f0;padding:2px 7px;border-radius:5px;font-size:0.8rem;font-weight:700;">${code}</code>`],
        ['<i class="bi bi-person"></i> Siswa / Kelas', `<strong>${student}</strong> <span style="font-size:0.75rem;color:#64748b;">(${cls})</span>`],
        ['<i class="bi bi-file-earmark-text"></i> Tagihan', bill],
        ['<i class="bi bi-wallet2"></i> Metode Bayar', `<span style="background:#e2e8f0;color:#334155;font-weight:700;padding:2px 8px;border-radius:6px;font-size:0.75rem;">${method}</span>`],
        ['<i class="bi bi-cash-stack"></i> Nominal', `<span style="color:#047857;font-weight:800;font-size:0.95rem;">${amount}</span>`],
        ['<i class="bi bi-clock"></i> Waktu Bayar', paidAt || '-'],
    ];

    if (proofUrl) {
        rows.push([
            '<i class="bi bi-image"></i> Bukti Transfer',
            `<a href="${proofUrl}" target="_blank" style="display:inline-flex;align-items:center;gap:4px;color:#0284c7;font-weight:700;font-size:0.82rem;text-decoration:underline;">
                <i class="bi bi-box-arrow-up-right"></i> Lihat Lampiran Bukti
            </a>`
        ]);
    }

    info.innerHTML = rows.map(([label, val]) => `
        <div style="display:flex;justify-content:space-between;align-items:center;padding:0.55rem 0;border-bottom:1px solid #e2e8f0;font-size:0.83rem;gap:0.75rem;">
            <span style="color:#64748b;flex-shrink:0;">${label}</span>
            <span style="text-align:right;">${val}</span>
        </div>`).join('');
    // Remove last border
    const divs = info.querySelectorAll('div');
    if (divs.length) divs[divs.length-1].style.borderBottom = 'none';

    // ── Warning box
    if (isApprove) {
        warning.style.background = '#ecfdf5';
        warning.style.border = '1px solid #a7f3d0';
        warning.style.color = '#065f46';
        warning.innerHTML = `
            <i class="bi bi-info-circle-fill" style="color:#059669;font-size:1.1rem;flex-shrink:0;margin-top:1px;"></i>
            <span>Setelah disetujui, sistem akan otomatis mengirimkan <strong>kwitansi digital</strong> dan status tagihan siswa akan berubah menjadi <strong>Lunas</strong>.</span>`;
    } else {
        warning.style.background = '#fef2f2';
        warning.style.border = '1px solid #fecaca';
        warning.style.color = '#991b1b';
        warning.innerHTML = `
            <i class="bi bi-exclamation-triangle-fill" style="color:#dc2626;font-size:1.1rem;flex-shrink:0;margin-top:1px;"></i>
            <span>Penolakan bersifat <strong>permanen</strong>. Siswa perlu mengajukan bukti pembayaran ulang. Pastikan alasan penolakan sudah dikomunikasikan.</span>`;
    }

    // ── Confirm button
    btn.disabled = false;
    btn.style.opacity = '1';
    btn.style.background = isApprove
        ? 'linear-gradient(135deg, #059669, #047857)'
        : 'linear-gradient(135deg, #ef4444, #dc2626)';
    btn.innerHTML = isApprove
        ? '<i class="bi bi-check-circle-fill"></i> Ya, Setujui Pembayaran'
        : '<i class="bi bi-x-circle-fill"></i> Ya, Tolak Pembayaran';

    // ── Form action
    const url = approveRoutes[id];
    const form = isApprove ? document.getElementById('approveForm') : document.getElementById('rejectForm');
    form.action = url;
    btn.onclick = () => {
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Memproses verifikasi...';
        form.submit();
    };

    // ── Show
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeVerifyModal() {
    document.getElementById('verifyModal').style.display = 'none';
    document.body.style.overflow = '';
}

// Close on Escape
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeVerifyModal(); });
</script>
@endsection
