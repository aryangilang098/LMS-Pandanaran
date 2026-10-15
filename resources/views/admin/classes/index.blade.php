@extends('layouts.app')

@section('title', 'Manajemen Kelas & Rombel')
@section('page_title', 'Kelas & Rombel')

@section('content')
<div class="cards-grid-2">
    {{-- Form Add Class --}}
    <div class="card">
        <div class="card-header"><h3>➕ Tambah Kelas Baru</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.classes.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Kelas</label>
                    <input type="text" name="name" class="form-input" required placeholder="contoh: X MIPA 3, XII IPS 1">
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" name="academic_year" class="form-input" value="{{ now()->year . '/' . (now()->year + 1) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Wali Kelas</label>
                    <input type="text" name="homeroom_teacher_name" class="form-input" placeholder="Nama wali kelas">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="bi bi-plus-lg"></i> Tambah Kelas
                </button>
            </form>
        </div>
    </div>

    {{-- List Classes --}}
    <div class="card">
        <div class="card-header"><h3>🏫 Daftar Kelas ({{ $classes->count() }})</h3></div>
        <div class="card-body" style="padding: 0; max-height: 500px; overflow-y: auto;">
            @forelse($classes as $c)
            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9;">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: linear-gradient(135deg, #047857, #059669); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">
                    {{ strtoupper(substr($c->name, 0, 2)) }}
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 700; font-size: 0.9rem; color: #1e293b;">{{ $c->name }}</div>
                    <div style="font-size: 0.75rem; color: #94a3b8;">{{ $c->academic_year }} · {{ $c->students_count }} siswa</div>
                    @if($c->homeroom_teacher_name)
                    <div style="font-size: 0.72rem; color: #64748b;">Wali: {{ $c->homeroom_teacher_name }}</div>
                    @endif
                </div>
                <span class="badge badge-emerald">{{ $c->students_count }} siswa</span>
            </div>
            @empty
            <div style="text-align: center; padding: 3rem; color: #94a3b8;">Belum ada kelas terdaftar.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
