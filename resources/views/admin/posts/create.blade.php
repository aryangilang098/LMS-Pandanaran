@extends('layouts.app')

@section('title', 'Tulis Artikel Baru')
@section('page_title', 'Tulis Artikel Baru')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h3>📝 Form Penulisan Artikel</h3>
            <a href="{{ route('admin.posts') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.posts.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Judul Artikel *</label>
                    <input type="text" name="title" class="form-input" required value="{{ old('title') }}" placeholder="Masukkan judul artikel yang menarik...">
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Kategori *</label>
                        <select name="category" class="form-input" required>
                            <option value="berita" {{ old('category', $selectedCategory ?? '') === 'berita' ? 'selected' : '' }}>📰 Berita</option>
                            <option value="agenda" {{ old('category', $selectedCategory ?? '') === 'agenda' ? 'selected' : '' }}>📅 Agenda</option>
                            <option value="prestasi" {{ old('category', $selectedCategory ?? '') === 'prestasi' ? 'selected' : '' }}>🏆 Prestasi / Jejak Keberhasilan</option>
                            <option value="pengumuman" {{ old('category', $selectedCategory ?? '') === 'pengumuman' ? 'selected' : '' }}>📢 Pengumuman</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Acara (khusus Agenda)</label>
                        <input type="date" name="event_date" class="form-input" value="{{ old('event_date') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Ringkasan / Excerpt</label>
                    <textarea name="excerpt" class="form-input" rows="2" placeholder="Ringkasan singkat artikel (max 500 karakter)..." maxlength="500">{{ old('excerpt') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Isi Artikel *</label>
                    <textarea name="content" class="form-input" rows="12" required placeholder="Tulis isi artikel lengkap di sini...">{{ old('content') }}</textarea>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <input type="checkbox" name="is_featured" id="is_featured" style="accent-color: #047857; width: 18px; height: 18px;">
                    <label for="is_featured" style="font-size: 0.875rem; font-weight: 600; cursor: pointer;">⭐ Tandai sebagai Artikel Unggulan (tampil di bagian utama landing page)</label>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem; font-size: 1rem;">
                    <i class="bi bi-send-fill"></i> Publikasikan Artikel
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
