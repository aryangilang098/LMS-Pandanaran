@extends('layouts.app')

@section('title', 'Kelas & Mata Pelajaran')
@section('page_title', 'E-Learning — Kelas & Mata Pelajaran')

@section('content')
@php $user = auth()->user(); @endphp

{{-- ─── ADMIN HEADER & FILTER ─────────────────────────────────────────── --}}
@if($user->isAdmin())
<div style="background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 16px; padding: 1.5rem; color: white; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div>
        <span style="background: rgba(59,130,246,0.3); color: #93c5fd; font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 50px; text-transform: uppercase;">
            <i class="bi bi-shield-check"></i> Mode Administrator — Hak Akses Penuh
        </span>
        <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0.5rem 0 0.25rem 0; color: white;">
            Seluruh Mata Pelajaran & Rombel Kelas
        </h3>
        <p style="font-size: 0.85rem; color: #94a3b8; margin: 0;">
            Sebagai Admin, Anda dapat memantau dan masuk ke ruang belajar mata pelajaran mana pun di semua kelas.
        </p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('admin.courses.plotting') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-diagram-3-fill"></i> Kelola Plotting
        </a>
    </div>
</div>

<div class="card" style="margin-bottom: 1.5rem; padding: 1rem 1.25rem;">
    <form method="GET" action="{{ route('lms.courses') }}" style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 220px;">
            <select name="class_id" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Rombel Kelas ({{ $courses->count() }} Mapel Aktif)</option>
                @foreach($classes ?? [] as $cl)
                    <option value="{{ $cl->id }}" {{ request('class_id') == $cl->id ? 'selected' : '' }}>
                        {{ $cl->name }} ({{ $cl->students_count }} Siswa)
                    </option>
                @endforeach
            </select>
        </div>
        <div style="flex: 1; min-width: 220px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama mata pelajaran..." onkeydown="if(event.key === 'Enter') this.form.submit()">
        </div>
        @if(request('class_id') || request('search'))
            <a href="{{ route('lms.courses') }}" class="btn btn-secondary">Reset Filter</a>
        @endif
    </form>
</div>

@elseif($user->isSiswa())
{{-- ─── SISWA INFO BANNER ──────────────────────────────────────────────── --}}
<div style="background: white; border-radius: 14px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; border: 1px solid var(--slate-200); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #ecfdf5; color: var(--emerald-600); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div>
            <div style="font-weight: 700; font-size: 0.92rem; color: var(--slate-800);">
                Daftar Mata Pelajaran Aktif Anda (Kelas {{ $user->schoolClass?->name ?? '-' }})
            </div>
            <div style="font-size: 0.78rem; color: var(--slate-500);">
                @if($isGrade10 ?? false)
                    <span class="badge badge-emerald" style="font-size: 0.7rem;">Kurikulum Paket Default Kelas 10</span> &bull; Semua mapel wajib diberikan otomatis.
                @else
                    <span class="badge badge-info" style="font-size: 0.7rem;">Tingkat Kelas {{ $studentClass?->getGradeLevel() ?? '11/12' }}</span> &bull; Terdiri dari Mapel Wajib + Mapel Peminatan yang Anda pilih.
                @endif
            </div>
        </div>
    </div>
    @if(!($isGrade10 ?? false))
    <a href="{{ route('lms.electives') }}" class="btn btn-secondary btn-sm" style="font-size: 0.78rem;">
        <i class="bi bi-bookmarks-fill"></i> Atur Mapel Peminatan
    </a>
    @endif
</div>
@endif

@if($courses->count())
<div class="course-cards-grid">
    @foreach($courses as $course)
    <a href="{{ route('lms.course.show', $course->id) }}" class="course-card">
        <div class="course-card-header" style="background: linear-gradient(135deg, {{ ['#047857,#059669','#1d4ed8,#3b82f6','#7c3aed,#8b5cf6','#b45309,#d97706','#0d9488,#14b8a6','#dc2626,#ef4444'][($course->id - 1) % 6] }});">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <span style="font-size: 1.8rem;">
                    @php
                    $icons = ['📖','🔬','🧮','🌐','⚛️','💻','🕌','📜','🧪','📐'];
                    echo $icons[($course->id - 1) % count($icons)];
                    @endphp
                </span>
                @if($course->subject?->is_elective)
                    <span style="background: #fef3c7; color: #92400e; font-size: 0.68rem; font-weight: 800; padding: 2px 8px; border-radius: 50px;">
                        Peminatan
                    </span>
                @endif
            </div>
            <h3>{{ $course->subject?->name }}</h3>
            <div style="font-size: 0.75rem; opacity: 0.85; font-family: monospace;">{{ $course->subject?->code }}</div>
        </div>
        <div class="course-card-body">
            <div class="course-meta">
                @if($user->isGuru() || $user->isAdmin())
                <div class="course-meta-item"><i class="bi bi-building"></i> <strong>{{ $course->schoolClass?->name ?? 'Semua Kelas' }}</strong></div>
                @endif
                @if(!$user->isGuru())
                <div class="course-meta-item"><i class="bi bi-person-badge"></i> {{ Str::words($course->teacher?->name ?? 'Pengampu', 3) }}</div>
                @endif
                <div class="course-meta-item"><i class="bi bi-calendar-week"></i> {{ $course->day ?? 'Fleksibel' }}, {{ $course->time_start }} – {{ $course->time_end }}</div>
                <div class="course-meta-item"><i class="bi bi-door-open"></i> {{ $course->room ?? 'Ruang Kelas' }}</div>
            </div>
            <div style="display: flex; gap: 0.4rem; flex-wrap: wrap; margin-top: 0.75rem;">
                <span style="background: #ecfdf5; color: #047857; padding: 2px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: 600;">
                    <i class="bi bi-file-earmark-text"></i> {{ $course->materials->count() }} Materi
                </span>
                <span style="background: #eff6ff; color: #1d4ed8; padding: 2px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: 600;">
                    <i class="bi bi-pencil-square"></i> {{ $course->assignments->count() }} Tugas
                </span>
                <span style="background: #f5f3ff; color: #7c3aed; padding: 2px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: 600;">
                    <i class="bi bi-patch-question"></i> {{ $course->quizzes->count() }} Kuis
                </span>
            </div>
        </div>
        <div class="course-card-footer">
            <div class="btn btn-primary" style="width: 100%; justify-content: center;">
                <i class="bi bi-box-arrow-in-right"></i> Masuk ke Kelas
            </div>
        </div>
    </a>
    @endforeach
</div>
@else
<div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 20px; border: 1px solid #e2e8f0;">
    <div style="font-size: 4rem; margin-bottom: 1rem;">📚</div>
    <h3 style="font-size: 1.2rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Belum Ada Mata Pelajaran Tersedia</h3>
    <p style="color: #64748b;">
        @if($user->isAdmin())
            Belum ada plotting mata pelajaran yang terdaftar. Silakan lakukan plotting di menu Plotting Mapel.
        @else
            Hubungi pihak akademik madrasah untuk informasi kurikulum kelas Anda.
        @endif
    </p>
</div>
@endif
@endsection
