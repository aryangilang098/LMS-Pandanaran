@extends('layouts.app')

@section('title', 'PPDB - Pendaftaran')
@section('page_title', 'PPDB Pendaftaran')

@section('content')
{{-- Stats Cards --}}
<div class="stats-grid" style="grid-template-columns: repeat(4, 1fr);">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="bi bi-people-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Pendaftar</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $stats['pending'] }}</div>
            <div class="stat-label">Menunggu Review</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-emerald"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $stats['approved'] }}</div>
            <div class="stat-label">Diterima</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="bi bi-x-circle-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $stats['rejected'] }}</div>
            <div class="stat-label">Ditolak</div>
        </div>
    </div>
</div>

{{-- Search & Filter --}}
<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form method="GET" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <input type="text" name="search" class="form-input" style="max-width: 300px;" placeholder="🔍 Cari nama, email, NISN, asal sekolah..." value="{{ request('search') }}">
            <select name="status" class="form-input" style="width: auto;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✅ Diterima</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Cari</button>
            <a href="{{ route('admin.registrations') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>📋 Daftar Pendaftar PPDB ({{ $registrations->total() }})</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama / NISN</th>
                        <th>Kontak</th>
                        <th>Asal Sekolah</th>
                        <th>Program</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $i => $reg)
                    <tr>
                        <td style="font-size: 0.8rem; color: #94a3b8;">{{ $registrations->firstItem() + $i }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.6rem;">
                                <div style="width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, #f59e0b, #d97706); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">
                                    {{ strtoupper(substr($reg->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; font-size: 0.875rem;">{{ $reg->name }}</div>
                                    <div style="font-size: 0.7rem; color: #94a3b8;">NISN: {{ $reg->nisn }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 0.8rem; color: #64748b;">{{ $reg->email }}</div>
                            <div style="font-size: 0.75rem; color: #94a3b8;">{{ $reg->phone }}</div>
                        </td>
                        <td style="font-size: 0.8rem;">{{ $reg->school }}</td>
                        <td>
                            <span class="badge badge-{{ $reg->program === 'tahfidz' ? 'success' : ($reg->program === 'sains' ? 'info' : ($reg->program === 'bilingual' ? 'purple' : 'warning')) }}">
                                {{ $reg->program_label }}
                            </span>
                        </td>
                        <td>
                            @if($reg->status === 'pending')
                                <span class="badge badge-warning">⏳ Menunggu</span>
                            @elseif($reg->status === 'approved')
                                <span class="badge badge-success">✅ Diterima</span>
                            @else
                                <span class="badge badge-danger">❌ Ditolak</span>
                            @endif
                        </td>
                        <td style="font-size: 0.78rem; color: #64748b;">{{ $reg->created_at->isoFormat('D MMM YYYY') }}</td>
                        <td>
                            <div style="display: flex; gap: 0.35rem; flex-wrap: wrap;">
                                @if($reg->status === 'pending')
                                    {{-- Approve Button --}}
                                    <button type="button" class="btn btn-primary btn-sm" onclick="showApproveModal({{ $reg->id }}, '{{ addslashes($reg->name) }}')" title="Terima">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    {{-- Reject Button --}}
                                    <form action="{{ route('admin.registrations.reject', $reg->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pendaftaran {{ addslashes($reg->name) }}?')" title="Tolak">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.registrations.delete', $reg->id) }}" method="POST" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Hapus data pendaftaran {{ addslashes($reg->name) }}?')" title="Hapus" style="color: #dc2626;">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem; color: #94a3b8;">
                            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                            Belum ada pendaftaran PPDB.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($registrations->hasPages())
        <div style="padding: 1rem 1.5rem; display: flex; justify-content: center;">
            {{ $registrations->appends(request()->all())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Approve Modal --}}
<div id="approveModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 2rem; max-width: 450px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.3s ease;">
        <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 0.5rem; color: #1e293b;">
            <i class="bi bi-check-circle-fill" style="color: #10b981;"></i> Terima Pendaftaran
        </h3>
        <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">
            Menerima <strong id="approveStudentName"></strong> sebagai siswa baru.
        </p>
        <form id="approveForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Pilih Kelas <span style="color: #dc2626;">*</span></label>
                <select name="class_id" class="form-input" required>
                    <option value="" disabled selected>-- Pilih kelas --</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }} ({{ $cls->academic_year }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan (Opsional)</label>
                <textarea name="admin_notes" class="form-input" rows="2" placeholder="Catatan untuk internal panitia..."></textarea>
            </div>
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeApproveModal()">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Terima & Buat Akun</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<style>
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>
<script>
function showApproveModal(id, name) {
    const modal = document.getElementById('approveModal');
    const form  = document.getElementById('approveForm');
    document.getElementById('approveStudentName').textContent = name;
    form.action = '/admin/ppdb/' + id + '/approve';
    modal.style.display = 'flex';
}
function closeApproveModal() {
    document.getElementById('approveModal').style.display = 'none';
}
document.getElementById('approveModal').addEventListener('click', function(e) {
    if (e.target === this) closeApproveModal();
});
</script>
@endsection
