@extends('layouts.app')

@section('title', 'Kelas & Mata Pelajaran')
@section('page_title', 'E-Learning — Kelas & Mata Pelajaran')

@section('content')
@php $user = auth()->user(); @endphp

<div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
    <p style="color: #64748b; font-size: 0.875rem;">
        @if($user->isGuru()) Kelola kelas dan mata pelajaran yang Anda ampu.
        @else Daftar mata pelajaran aktif kelas {{ $user->schoolClass?->name ?? 'Anda' }}.
        @endif
    </p>
</div>

@if($courses->count())
<div class="course-cards-grid">
    @foreach($courses as $course)
    <a href="{{ route('lms.course.show', $course->id) }}" class="course-card">
        <div class="course-card-header" style="background: linear-gradient(135deg, {{ ['#047857,#059669','#1d4ed8,#3b82f6','#7c3aed,#8b5cf6','#b45309,#d97706','#0d9488,#14b8a6','#dc2626,#ef4444'][($course->id - 1) % 6] }});">
            <div style="font-size: 1.8rem; margin-bottom: 0.5rem;">
                @php
                $icons = ['📖','🔬','🧮','🌐','⚛️','💻','🕌','📜','🧪','📐'];
                echo $icons[($course->id - 1) % count($icons)];
                @endphp
            </div>
            <h3>{{ $course->subject->name }}</h3>
        </div>
        <div class="course-card-body">
            <div class="course-meta">
                @if($user->isGuru())
                <div class="course-meta-item"><i class="bi bi-building2"></i> {{ $course->schoolClass->name }}</div>
                @else
                <div class="course-meta-item"><i class="bi bi-person-badge"></i> {{ Str::words($course->teacher->name, 3) }}</div>
                @endif
                <div class="course-meta-item"><i class="bi bi-calendar-week"></i> {{ $course->day }}, {{ $course->time_start }} – {{ $course->time_end }}</div>
                <div class="course-meta-item"><i class="bi bi-door-open"></i> {{ $course->room ?? 'Online' }}</div>
            </div>
            <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
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
    <h3 style="font-size: 1.2rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Belum Ada Kelas Tersedia</h3>
    <p style="color: #64748b;">Hubungi administrator untuk mendapatkan akses ke kelas.</p>
</div>
@endif
@endsection
