@extends('layouts.app')

@section('title', 'Pantau Seluruh Mata Pelajaran')
@section('page_title', 'Pantau KBM Seluruh Mapel & Kelas')

@section('content')
<div class="content-header" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-800); margin: 0;">Pantau KBM Seluruh Mata Pelajaran</h2>
        <p style="font-size: 0.85rem; color: var(--slate-500); margin-top: 0.25rem;">
            Sebagai Admin, Anda memiliki hak akses penuh untuk mengamati materi, tugas, presensi, dan masuk ke setiap ruang kelas mata pelajaran.
        </p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('admin.courses.plotting') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-diagram-3-fill"></i> Kelola Plotting
        </a>
        <a href="{{ route('admin.subjects') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-book-half"></i> Master Mapel
        </a>
    </div>
</div>

{{-- ─── FILTER & PENCARIAN ─────────────────────────────────────────────── --}}
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form method="GET" action="{{ route('admin.courses.monitor') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)) 120px; gap: 1rem; align-items: end;">
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--slate-600); margin-bottom: 0.35rem; display: block;">Filter Berdasarkan Rombel Kelas</label>
            <select name="class_id" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Kelas ({{ $courses->count() }} Mapel Aktif)</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }} ({{ $c->students_count }} Siswa)
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--slate-600); margin-bottom: 0.35rem; display: block;">Cari Nama Mata Pelajaran</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Ketik nama mapel..." onkeydown="if(event.key === 'Enter') this.form.submit()">
        </div>
        <div>
            <a href="{{ route('admin.courses.monitor') }}" class="btn btn-secondary" style="width: 100%; justify-content: center;">Reset</a>
        </div>
    </form>
</div>

{{-- ─── GRID KARTU MATA PELAJARAN UNTUK ADMIN ──────────────────────────── --}}
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.25rem;">
    @forelse($courses as $c)
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s, box-shadow 0.2s; border-radius: 16px; overflow: hidden; border: 1px solid var(--slate-200);">
        <div style="background: linear-gradient(135deg, var(--emerald-800), var(--emerald-900)); padding: 1.25rem; color: white; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                <span style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 50px;">
                    <i class="bi bi-building"></i> {{ $c->schoolClass?->name ?? 'Semua Kelas' }}
                </span>
                @if($c->subject?->is_elective)
                    <span style="background: #f59e0b; color: #78350f; font-size: 0.72rem; font-weight: 800; padding: 3px 8px; border-radius: 50px;">
                        Peminatan
                    </span>
                @else
                    <span style="background: rgba(255,255,255,0.15); font-size: 0.72rem; font-weight: 700; padding: 3px 8px; border-radius: 50px;">
                        Wajib
                    </span>
                @endif
            </div>
            <h3 style="font-size: 1.15rem; font-weight: 800; color: white; margin: 0 0 0.25rem 0; line-height: 1.3;">
                {{ $c->subject?->name }}
            </h3>
            <div style="font-size: 0.78rem; opacity: 0.85; font-family: monospace;">
                Kode: {{ $c->subject?->code }} &bull; Kategori: {{ ucfirst($c->subject?->category ?? 'Umum') }}
            </div>
        </div>

        <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                {{-- Guru Pengampu --}}
                <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.72rem; color: var(--slate-400); font-weight: 600;">Guru Pengampu:</div>
                        <div style="font-size: 0.88rem; font-weight: 700; color: var(--slate-800);">
                            {{ $c->teacher?->name ?? 'Belum Ditugaskan' }}
                        </div>
                    </div>
                </div>

                {{-- Status Pembelajaran --}}
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; text-align: center; margin-bottom: 1rem;">
                    <div style="background: #f8fafc; padding: 0.6rem; border-radius: 10px; border: 1px solid #f1f5f9;">
                        <div style="font-size: 1.1rem; font-weight: 800; color: var(--emerald-600);">{{ $c->materials->count() }}</div>
                        <div style="font-size: 0.68rem; color: var(--slate-500); font-weight: 600;">Materi</div>
                    </div>
                    <div style="background: #f8fafc; padding: 0.6rem; border-radius: 10px; border: 1px solid #f1f5f9;">
                        <div style="font-size: 1.1rem; font-weight: 800; color: #2563eb;">{{ $c->assignments->count() }}</div>
                        <div style="font-size: 0.68rem; color: var(--slate-500); font-weight: 600;">Tugas</div>
                    </div>
                    <div style="background: #f8fafc; padding: 0.6rem; border-radius: 10px; border: 1px solid #f1f5f9;">
                        <div style="font-size: 1.1rem; font-weight: 800; color: #7c3aed;">{{ $c->quizzes->count() }}</div>
                        <div style="font-size: 0.68rem; color: var(--slate-500); font-weight: 600;">Kuis</div>
                    </div>
                </div>

                {{-- Jadwal & Ruangan --}}
                <div style="font-size: 0.78rem; color: var(--slate-600); margin-bottom: 1.25rem;">
                    <i class="bi bi-clock-history"></i> 
                    {{ $c->day ? $c->day . ', ' . ($c->time_start ?? '') . ' - ' . ($c->time_end ?? '') : 'Jadwal Fleksibel' }}
                    @if($c->room) &bull; Ruang: {{ $c->room }} @endif
                </div>
            </div>

            {{-- Tombol Masuk --}}
            <a href="{{ route('lms.course.show', $c->id) }}" class="btn btn-primary" style="width: 100%; justify-content: center; gap: 0.5rem; font-weight: 700;">
                <i class="bi bi-box-arrow-in-right"></i> Masuk & Pantau Mapel Ini
            </a>
        </div>
    </div>
    @empty
    <div class="card" style="padding: 3rem; text-align: center; color: var(--slate-400); grid-column: 1 / -1;">
        <i class="bi bi-binoculars" style="font-size: 3rem; display: block; margin-bottom: 0.5rem;"></i>
        Tidak ditemukan mata pelajaran yang cocok dengan filter.
    </div>
    @endforelse
</div>
@endsection
