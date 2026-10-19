@extends('layouts.app')

@section('title', 'Manajemen Mapel Peminatan (Kelas 11-12)')
@section('page_title', 'Peminatan Siswa (Kelas 11 & 12)')

@section('content')
<div class="content-header" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-800); margin: 0;">Peminatan Siswa Kelas 11 & 12</h2>
        <p style="font-size: 0.85rem; color: var(--slate-500); margin-top: 0.25rem;">
            Siswa kelas 10 menerima mapel default. Untuk kelas 11-12, admin memunculkan mapel peminatan yang dapat dipilih oleh siswa atau dikelola oleh admin.
        </p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('admin.subjects', ['type' => 'elective']) }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-gear-fill"></i> Atur Mapel Peminatan
        </a>
        <a href="{{ route('admin.courses.plotting') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-diagram-3-fill"></i> Plotting ke Kelas
        </a>
    </div>
</div>

{{-- ─── INFO BANNER ─────────────────────────────────────────────────────── --}}
<div style="background: linear-gradient(135deg, #1e3a8a, #2563eb); border-radius: 14px; padding: 1.25rem 1.5rem; color: white; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
            <i class="bi bi-info-circle-fill"></i>
        </div>
        <div>
            <div style="font-weight: 700; font-size: 0.95rem;">Kebijakan Kurikulum Peminatan:</div>
            <div style="font-size: 0.82rem; opacity: 0.9; margin-top: 0.2rem;">
                Kelas 10 mengikuti <strong>Kurikulum Paket Default</strong>. Setelah naik ke Kelas 11 & 12, menu Peminatan otomatis aktif di portal siswa untuk memilih bidang studi lanjutan sesuai minat mereka.
            </div>
        </div>
    </div>
</div>

{{-- ─── STATISTIK MAPEL PEMINATAN YANG DIBUKA ADMIN ─────────────────────── --}}
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--slate-800); margin-bottom: 0.75rem;">
        🎯 Mata Pelajaran Peminatan yang Sedang Dibuka Admin
    </h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
        @forelse($electiveSubjects as $el)
        <div class="card" style="padding: 1.25rem; border-left: 4px solid var(--gold-500);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                <span class="badge" style="background: #fef3c7; color: #b45309; font-weight: 700; font-size: 0.75rem;">
                    {{ $el->code }}
                </span>
                <span class="badge badge-info" style="font-size: 0.72rem;">
                    Tingkat: {{ $el->grade_level === 'all' ? '11 & 12' : 'Kls ' . $el->grade_level }}
                </span>
            </div>
            <div style="font-weight: 700; font-size: 1rem; color: var(--slate-800); margin-bottom: 0.35rem;">
                {{ $el->name }}
            </div>
            <div style="font-size: 0.78rem; color: var(--slate-500); display: flex; align-items: center; justify-content: space-between;">
                <span>Kategori: {{ ucfirst($el->category) }}</span>
                <strong style="color: var(--emerald-600);"><i class="bi bi-people-fill"></i> {{ $el->elective_students_count }} Siswa Terdaftar</strong>
            </div>
        </div>
        @empty
        <div class="card" style="padding: 1.5rem; text-align: center; color: var(--slate-400); grid-column: 1 / -1;">
            Belum ada mata pelajaran peminatan yang diaktifkan. Silakan buka menu <strong>Master Mapel</strong> dan tandai mapel sebagai "Peminatan".
        </div>
        @endforelse
    </div>
</div>

{{-- ─── FILTER KELAS 11 & 12 ───────────────────────────────────────────── --}}
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form method="GET" action="{{ route('admin.electives') }}" style="display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 260px;">
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.35rem; display: block;">
                Pilih Kelas 11 atau 12 untuk Memantau / Mengelola Pilihan Siswa:
            </label>
            <select name="class_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Pilih Rombel Kelas 11 / 12 --</option>
                @foreach($classes as $cl)
                    <option value="{{ $cl->id }}" {{ $selectedClassId == $cl->id ? 'selected' : '' }}>
                        {{ $cl->name }} ({{ $cl->students_count }} Siswa) - Tahun {{ $cl->academic_year }}
                    </option>
                @endforeach
            </select>
        </div>
        @if($selectedClassId)
            <a href="{{ route('admin.electives') }}" class="btn btn-secondary">Reset Pilihan</a>
        @endif
    </form>
</div>

{{-- ─── TABEL SISWA DAN MAPEL PEMINATAN ─────────────────────────────────── --}}
@if($selectedClassId)
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 1rem; font-weight: 700; color: var(--slate-800); margin: 0;">
            Daftar Pilihan Peminatan Siswa ({{ $students->count() }} Siswa)
        </h3>
        <span style="font-size: 0.8rem; color: var(--slate-500);">
            *Admin dapat mendaftarkan langsung atau siswa memilih sendiri di portal siswa
        </span>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Siswa / NISN</th>
                    <th>Peminatan yang Diambil</th>
                    <th style="width: 250px; text-align: right;">Aksi Cepat Admin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $idx => $st)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                        <div style="font-weight: 700; color: var(--slate-800);">{{ $st->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--slate-400);">NISN: {{ $st->identity_number ?? '-' }} &bull; {{ $st->email }}</div>
                    </td>
                    <td>
                        @if($st->electiveSubjects->count() > 0)
                            <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                                @foreach($st->electiveSubjects as $sub)
                                <span class="badge" style="background: #e0e7ff; color: #3730a3; padding: 5px 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    {{ $sub->name }}
                                    <form action="{{ route('admin.electives.toggle') }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus mapel {{ $sub->name }} dari siswa ini?');">
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{ $st->id }}">
                                        <input type="hidden" name="subject_id" value="{{ $sub->id }}">
                                        <input type="hidden" name="action" value="drop">
                                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0; font-size: 0.85rem; line-height: 1;" title="Batalkan Pilihan">
                                            &times;
                                        </button>
                                    </form>
                                </span>
                                @endforeach
                            </div>
                        @else
                            <span class="badge badge-secondary" style="color: #64748b;">Belum Memilih Peminatan</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <form action="{{ route('admin.electives.toggle') }}" method="POST" style="display: inline-flex; gap: 0.35rem; align-items: center;">
                            @csrf
                            <input type="hidden" name="student_id" value="{{ $st->id }}">
                            <input type="hidden" name="action" value="enroll">
                            <select name="subject_id" class="form-control form-control-sm" style="font-size: 0.78rem; padding: 0.25rem 0.5rem; max-width: 170px;" required>
                                <option value="">+ Daftarkan Mapel</option>
                                @foreach($electiveSubjects as $elSub)
                                    @if(!$st->electiveSubjects->contains('id', $elSub->id))
                                        <option value="{{ $elSub->id }}">{{ $elSub->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;">
                                Simpan
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 2rem; color: var(--slate-400);">
                        Belum ada data siswa di kelas ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card" style="padding: 3rem; text-align: center; color: var(--slate-400);">
    <i class="bi bi-arrow-up-circle" style="font-size: 2.5rem; display: block; margin-bottom: 0.75rem; color: var(--emerald-600);"></i>
    <div style="font-size: 1.05rem; font-weight: 700; color: var(--slate-700);">Pilih salah satu kelas di atas untuk melihat data peminatan siswa.</div>
    <p style="font-size: 0.85rem; margin-top: 0.35rem;">Menu ini khusus untuk siswa tingkat kelas 11 dan 12 yang memiliki opsi peminatan mata pelajaran.</p>
</div>
@endif
@endsection
