@extends('layouts.app')

@section('title', request('category') === 'prestasi' ? 'Jejak Keberhasilan Santri' : 'Manajemen Berita & Pengumuman')
@section('page_title', request('category') === 'prestasi' ? 'Jejak Keberhasilan Santri' : 'Berita & Pengumuman')

@section('content')
<div style="display: flex; justify-content: flex-end; margin-bottom: 1.25rem;">
    <a href="{{ route('admin.posts.create', ['category' => request('category')]) }}" class="btn btn-gold">
        <i class="bi bi-plus-lg"></i> {{ request('category') === 'prestasi' ? 'Tambah Prestasi Baru' : 'Tulis Artikel Baru' }}
    </a>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form method="GET" style="display: flex; gap: 0.75rem; align-items: center;">
            <select name="category" class="form-input" style="width: auto;" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach(['berita','agenda','prestasi','pengumuman'] as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.posts') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>{{ request('category') === 'prestasi' ? '🏆 Daftar Jejak Keberhasilan Santri' : '📰 Daftar Artikel' }} ({{ $posts->total() }})</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Unggulan</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($posts as $p)
                    <tr>
                        <td style="max-width: 300px;">
                            <div style="font-weight: 700; font-size: 0.875rem;">{{ Str::limit($p->title, 60) }}</div>
                        </td>
                        <td><span class="badge badge-{{ $p->category === 'prestasi' ? 'warning' : ($p->category === 'pengumuman' ? 'info' : ($p->category === 'agenda' ? 'purple' : 'emerald')) }}">{{ ucfirst($p->category) }}</span></td>
                        <td style="font-size: 0.8rem; color: #64748b;">{{ Str::words($p->author->name, 2) }}</td>
                        <td>{{ $p->is_featured ? '⭐' : '—' }}</td>
                        <td style="font-size: 0.78rem; color: #94a3b8; white-space: nowrap;">{{ $p->published_at?->isoFormat('D MMM YYYY') }}</td>
                        <td>
                            <div style="display: flex; gap: 0.35rem;">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:6px;background:#f1f5f9;color:#94a3b8;" title="Halaman publik dihapus"><i class="bi bi-eye-slash"></i></span>
                                <form action="{{ route('admin.posts.delete', $p->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus artikel ini?')">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align: center; padding: 3rem; color: #94a3b8;">Belum ada artikel.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($posts->hasPages())
        <div style="padding: 1rem 1.5rem; display: flex; justify-content: center;">{{ $posts->links() }}</div>
        @endif
    </div>
</div>
@endsection
