@extends('layouts.app')

@section('title', 'Nilai UTS & UAS - ' . $course->subject->name)
@section('page_title', 'Nilai UTS & UAS')
@section('breadcrumb', 'Input Nilai Ujian')

@section('content')
@php $isTeacher = $user->isGuru() || $user->isAdmin(); @endphp

<div style="max-width: 1000px; margin: 0 auto;">

    {{-- HEADER CARD --}}
    <div style="background: linear-gradient(135deg, #064e3b, #047857); border-radius: 20px; padding: 1.75rem 2rem; color: white; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="font-size: 0.8rem; color: rgba(255,255,255,0.7); margin-bottom: 0.35rem;">
                <a href="{{ route('lms.course.show', $course->id) }}" style="color: rgba(255,255,255,0.85); text-decoration: underline;">← Kembali ke Kelas</a>
            </div>
            <h2 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 0.35rem;">Input Nilai UTS & UAS: {{ $course->subject->name }}</h2>
            <div style="font-size: 0.85rem; color: rgba(255,255,255,0.8); display: flex; gap: 1rem; flex-wrap: wrap;">
                <span>🏫 Kelas: <strong>{{ $course->schoolClass->name }}</strong></span>
                <span>👨‍🏫 Pengampu: <strong>{{ $course->teacher->name }}</strong></span>
                <span>👥 Total Siswa: <strong>{{ $students->count() }} Siswa</strong></span>
            </div>
        </div>
        <div>
            <span style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); padding: 0.5rem 1rem; border-radius: 12px; font-size: 0.82rem; font-weight: 600;">
                {{ $isTeacher ? '✍️ Mode Input Guru' : '👁️ Mode Lihat Siswa' }}
            </span>
        </div>
    </div>

    @if(session('success'))
    <div style="background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; padding: 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-weight: 600;">
        ✅ {{ session('success') }}
    </div>
    @endif

    {{-- MAIN CONTENT --}}
    <div class="card" style="border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
        <div class="card-header" style="border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.05rem; font-weight: 700; color: #0f172a;">
                📋 Daftar Nilai UTS & UAS Siswa {{ $course->schoolClass->name }}
            </h3>
            @if($isTeacher)
            <span style="font-size: 0.78rem; color: #64748b;">Hanya Guru Pengampu / Admin yang dapat mengubah nilai ini.</span>
            @endif
        </div>
        <div class="card-body" style="padding: 0;">

            @if($isTeacher)
            {{-- FORM INPUT GURU --}}
            <form action="{{ route('lms.exam_grades.store', $course->id) }}" method="POST">
                @csrf

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">No</th>
                                <th>Nama Siswa</th>
                                <th style="width: 150px; text-align: center;">Nilai UTS (0–100)</th>
                                <th style="width: 150px; text-align: center;">Nilai UAS (0–100)</th>
                                <th style="width: 120px; text-align: center;">Rata-Rata Ujian</th>
                                <th>Catatan Guru</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $index => $student)
                            @php
                                $grade = $examGrades->get($student->id);
                                $uts = $grade?->score_uts;
                                $uas = $grade?->score_uas;
                                $avg = ($uts !== null && $uas !== null) ? round(($uts + $uas) / 2, 1) : ($uts ?? $uas ?? '-');
                            @endphp
                            <tr>
                                <td style="text-align: center; font-weight: 600; color: #64748b;">{{ $index + 1 }}</td>
                                <td>
                                    <div style="font-weight: 700; color: #1e293b;">{{ $student->name }}</div>
                                    <div style="font-size: 0.75rem; color: #94a3b8;">NISN: {{ $student->identity_number ?? '-' }}</div>
                                </td>
                                <td>
                                    <input type="number" step="0.1" min="0" max="100"
                                        name="grades[{{ $student->id }}][score_uts]"
                                        value="{{ old("grades.{$student->id}.score_uts", $uts) }}"
                                        class="form-input" placeholder="0 - 100" style="text-align: center; font-weight: 700; color: #047857;">
                                </td>
                                <td>
                                    <input type="number" step="0.1" min="0" max="100"
                                        name="grades[{{ $student->id }}][score_uas]"
                                        value="{{ old("grades.{$student->id}.score_uas", $uas) }}"
                                        class="form-input" placeholder="0 - 100" style="text-align: center; font-weight: 700; color: #1d4ed8;">
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-emerald" style="font-size: 0.85rem; font-weight: 800;">{{ $avg }}</span>
                                </td>
                                <td>
                                    <input type="text"
                                        name="grades[{{ $student->id }}][notes]"
                                        value="{{ old("grades.{$student->id}.notes", $grade?->notes) }}"
                                        class="form-input" placeholder="Catatan perkembangan...">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: #94a3b8; padding: 2.5rem;">
                                    Belum ada siswa terdaftar pada kelas ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($students->count())
                <div style="padding: 1.25rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; text-align: right;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.75rem; font-size: 0.95rem; font-weight: 700;">
                        <i class="bi bi-save-fill"></i> Simpan Nilai UTS & UAS
                    </button>
                </div>
                @endif

            </form>

            @else
            {{-- TABEL TAMPILAN SISWA --}}
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th style="text-align: center;">Nilai UTS</th>
                            <th style="text-align: center;">Nilai UAS</th>
                            <th style="text-align: center;">Rata-Rata Ujian</th>
                            <th>Catatan Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $myGrade = $examGrades->get($user->id);
                            $uts = $myGrade?->score_uts;
                            $uas = $myGrade?->score_uas;
                            $avg = ($uts !== null && $uas !== null) ? round(($uts + $uas) / 2, 1) : ($uts ?? $uas ?? '-');
                        @endphp
                        <tr>
                            <td style="font-weight: 700;">{{ $course->subject->name }}</td>
                            <td style="text-align: center; font-size: 1.1rem; font-weight: 800; color: #047857;">
                                {{ $uts ?? 'Belum Diinput' }}
                            </td>
                            <td style="text-align: center; font-size: 1.1rem; font-weight: 800; color: #1d4ed8;">
                                {{ $uas ?? 'Belum Diinput' }}
                            </td>
                            <td style="text-align: center;">
                                <span class="badge badge-emerald" style="font-size: 0.9rem; font-weight: 800;">{{ $avg }}</span>
                            </td>
                            <td style="color: #64748b; font-size: 0.85rem;">
                                {{ $myGrade?->notes ?? '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection
