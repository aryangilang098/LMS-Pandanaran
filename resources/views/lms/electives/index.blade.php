@extends('layouts.app')

@section('title', 'Pemilihan Mata Pelajaran Peminatan')
@section('page_title', 'Mata Pelajaran Peminatan')

@section('content')
<div class="content-header" style="margin-bottom: 1.5rem;">
    <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-800); margin: 0;">Peminatan Mata Pelajaran Siswa</h2>
    <p style="font-size: 0.85rem; color: var(--slate-500); margin-top: 0.25rem;">
        Kelas Anda: <strong>{{ $studentClass?->name ?? 'Belum Ditentukan' }}</strong> &bull; Tingkat: <strong>Kelas {{ $gradeLevel }}</strong>
    </p>
</div>

@if(!$isSenior)
{{-- ─── TAMPILAN UNTUK SISWA KELAS 10 (MAPEL DEFAULT) ──────────────────── --}}
<div class="card" style="padding: 2.5rem; text-align: center; max-width: 680px; margin: 0 auto; border-top: 5px solid var(--emerald-600);">
    <div style="width: 72px; height: 72px; border-radius: 50%; background: #ecfdf5; color: var(--emerald-600); display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 1.25rem auto;">
        <i class="bi bi-shield-check"></i>
    </div>
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-800); margin-bottom: 0.5rem;">
        Kurikulum Siswa Kelas 10 (Paket Default)
    </h3>
    <p style="font-size: 0.9rem; color: var(--slate-600); line-height: 1.6; margin-bottom: 1.5rem;">
        Sebagai siswa kelas 10, Anda secara otomatis diberikan seluruh <strong>Mata Pelajaran Wajib / Default</strong> oleh pihak madrasah untuk membangun pondasi akademik dan keagamaan yang kokoh.
    </p>
    <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 1.25rem; text-align: left; margin-bottom: 1.5rem;">
        <div style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="bi bi-info-circle-fill" style="color: var(--emerald-600);"></i> Informasi Penjurusan & Peminatan:
        </div>
        <p style="font-size: 0.82rem; color: var(--slate-500); margin: 0; line-height: 1.5;">
            Pemilihan mata pelajaran peminatan (seperti Sains Lanjutan, Sosial Humaniora, atau Pendalaman Kitab Keagamaan) akan dibuka dan dapat Anda pilih secara mandiri setelah <strong>beranjak naik ke Kelas 11 dan 12</strong>.
        </p>
    </div>
    <a href="{{ route('lms.courses') }}" class="btn btn-primary" style="justify-content: center;">
        <i class="bi bi-mortarboard-fill"></i> Lihat Daftar Mapel Kelas 10 Saya
    </a>
</div>

@else
{{-- ─── TAMPILAN UNTUK SISWA KELAS 11 & 12 (BISA MEMILIH PEMINATAN) ─────── --}}
<div style="background: linear-gradient(135deg, #064e3b, #047857); border-radius: 16px; padding: 1.5rem; color: white; margin-bottom: 1.75rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div>
        <span style="background: rgba(255,255,255,0.2); font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 50px; text-transform: uppercase;">
            Tingkat Lanjutan
        </span>
        <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0.5rem 0 0.25rem 0; color: white;">
            Pilih Mata Pelajaran Peminatan Anda
        </h3>
        <p style="font-size: 0.85rem; color: rgba(255,255,255,0.85); margin: 0;">
            Silakan pilih mata pelajaran peminatan sesuai minat, bakat, dan rencana program studi kuliah Anda.
        </p>
    </div>
    <div style="text-align: right; background: rgba(255,255,255,0.12); padding: 0.75rem 1.25rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2);">
        <div style="font-size: 1.5rem; font-weight: 800;">{{ count($chosenSubjectIds) }}</div>
        <div style="font-size: 0.75rem; opacity: 0.85;">Mapel Peminatan Dipilih</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem;">
    @forelse($availableElectives as $sub)
    @php
        $isChosen = in_array($sub->id, $chosenSubjectIds);
    @endphp
    <div class="card" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; border-radius: 16px; border: 2px solid {{ $isChosen ? 'var(--emerald-500)' : 'var(--slate-200)' }}; position: relative; background: {{ $isChosen ? '#f0fdf4' : 'white' }};">
        @if($isChosen)
        <div style="position: absolute; top: -10px; right: 15px; background: var(--emerald-600); color: white; font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 50px; box-shadow: 0 2px 6px rgba(5,150,105,0.3);">
            <i class="bi bi-check-circle-fill"></i> TERPILIH
        </div>
        @endif

        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <span class="badge" style="background: #f1f5f9; color: var(--slate-700); font-family: monospace; font-weight: 700;">
                    {{ $sub->code }}
                </span>
                <span class="badge badge-warning" style="font-size: 0.72rem;">
                    Peminatan {{ ucfirst($sub->category) }}
                </span>
            </div>

            <h4 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-800); margin: 0 0 0.5rem 0;">
                {{ $sub->name }}
            </h4>

            <p style="font-size: 0.8rem; color: var(--slate-500); line-height: 1.5; margin-bottom: 1.25rem;">
                Disediakan khusus untuk pengembangan minat santri kelas {{ $sub->grade_level === 'all' ? '11 & 12' : $sub->grade_level }}.
            </p>

            <div style="font-size: 0.75rem; color: var(--slate-400); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.35rem;">
                <i class="bi bi-people"></i> Dipilih oleh <strong>{{ $sub->elective_students_count }} siswa</strong> angkatan Anda
            </div>
        </div>

        <form action="{{ route('lms.electives.toggle') }}" method="POST">
            @csrf
            <input type="hidden" name="subject_id" value="{{ $sub->id }}">

            @if($isChosen)
                <input type="hidden" name="action" value="drop">
                <button type="submit" class="btn btn-danger" style="width: 100%; justify-content: center; font-weight: 700;" onclick="return confirm('Batalkan pilihan mata pelajaran peminatan ini?');">
                    <i class="bi bi-x-circle"></i> Batalkan Pilihan Mapel
                </button>
            @else
                <input type="hidden" name="action" value="enroll">
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-weight: 700;">
                    <i class="bi bi-plus-circle"></i> Pilih Mata Pelajaran Ini
                </button>
            @endif
        </form>
    </div>
    @empty
    <div class="card" style="padding: 3rem; text-align: center; color: var(--slate-400); grid-column: 1 / -1;">
        <i class="bi bi-journal-x" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
        Belum ada mata pelajaran peminatan yang dibuka oleh admin untuk tingkat kelas Anda.
    </div>
    @endforelse
</div>
@endif
@endsection
