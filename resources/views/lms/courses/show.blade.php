@extends('layouts.app')

@section('title', $course->subject->name)
@section('page_title', $course->subject->name)
@section('breadcrumb', 'Detail Kelas')

@section('content')
@php $user = auth()->user(); @endphp

{{-- HEADER --}}
<div style="background: linear-gradient(135deg, #064e3b, #047857); border-radius: 20px; padding: 2rem; margin-bottom: 1.75rem; color: white;">
    <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="font-size: 0.78rem; color: rgba(255,255,255,0.65); margin-bottom: 0.4rem;">
                <a href="{{ route('lms.courses') }}" style="color: rgba(255,255,255,0.65);">← Kembali ke Daftar Kelas</a>
            </div>
            <h2 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.5rem;">{{ $course->subject->name }}</h2>
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; font-size: 0.82rem; color: rgba(255,255,255,0.8);">
                <span><i class="bi bi-building2"></i> {{ $course->schoolClass->name }}</span>
                <span><i class="bi bi-person-badge"></i> {{ $course->teacher->name }}</span>
                <span><i class="bi bi-calendar-week"></i> {{ $course->day }}, {{ $course->time_start }} – {{ $course->time_end }}</span>
                <span><i class="bi bi-door-open"></i> {{ $course->room ?? 'Online' }}</span>
            </div>
            @if($course->description)
            <p style="margin-top: 0.75rem; font-size: 0.875rem; color: rgba(255,255,255,0.75); max-width: 700px; line-height: 1.6;">{{ $course->description }}</p>
            @endif
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('lms.exam_grades', $course->id) }}" class="btn btn-sm" style="background: #10b981; color: white; border: none; font-weight: 600;"><i class="bi bi-award-fill"></i> Nilai UTS & UAS</a>
            @if($user->isGuru() && $course->teacher_id === $user->id)
            <a href="{{ route('lms.material.create', $course->id) }}" class="btn btn-gold btn-sm"><i class="bi bi-plus-lg"></i> Tambah Materi</a>
            <a href="{{ route('lms.assignment.create', $course->id) }}" class="btn btn-outline btn-sm" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3); color: white;"><i class="bi bi-plus-lg"></i> Buat Tugas</a>
            @endif
        </div>
    </div>
</div>

{{-- TABS --}}
<div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; overflow-x: auto;">
    @foreach([['materi','Materi','file-earmark-text-fill'],['tugas','Tugas','pencil-square'],['kuis','Kuis Online','patch-question-fill'],['presensi','Presensi','calendar-check-fill']] as [$id, $label, $icon])
    <button onclick="showTab('{{ $id }}')" id="tab-{{ $id }}"
        style="padding: 0.6rem 1.25rem; border-radius: 10px; font-size: 0.83rem; font-weight: 600; white-space: nowrap; border: 1.5px solid #e2e8f0; background: white; color: #64748b; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.4rem;">
        <i class="bi bi-{{ $icon }}"></i> {{ $label }}
    </button>
    @endforeach
</div>

{{-- TAB: MATERI --}}
<div id="panel-materi" class="tab-panel">
    @if($course->materials->count())
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        @foreach($course->materials as $m)
        <div style="background: white; border-radius: 14px; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; align-items: flex-start; gap: 1rem;">
            <div style="width: 48px; height: 48px; border-radius: 12px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
                background: {{ $m->file_type === 'pdf' ? '#fee2e2' : ($m->file_type === 'link' ? '#eff6ff' : ($m->file_type === 'video' ? '#f5f3ff' : '#ecfdf5')) }};">
                {{ $m->file_type === 'pdf' ? '📄' : ($m->file_type === 'link' ? '🔗' : ($m->file_type === 'video' ? '🎬' : '📁')) }}
            </div>
            <div style="flex: 1;">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 0.3rem;">{{ $m->title }}</h4>
                @if($m->content)
                <p style="font-size: 0.82rem; color: #64748b; line-height: 1.6; margin-bottom: 0.5rem;">{{ Str::limit($m->content, 200) }}</p>
                @endif
                <div style="font-size: 0.75rem; color: #94a3b8;">{{ $m->created_at->isoFormat('D MMMM YYYY') }}</div>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                @if($m->external_link)
                <a href="{{ $m->external_link }}" target="_blank" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-up-right"></i> Buka</a>
                @elseif($m->file_path)
                <a href="{{ Storage::url($m->file_path) }}" target="_blank" class="btn btn-primary btn-sm"><i class="bi bi-download"></i> Unduh</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align: center; padding: 3rem; background: white; border-radius: 16px; border: 1px solid #e2e8f0;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📂</div>
        <p style="color: #94a3b8; font-size: 0.875rem;">Belum ada materi yang diunggah.</p>
        @if($user->isGuru() && $course->teacher_id === $user->id)
        <a href="{{ route('lms.material.create', $course->id) }}" class="btn btn-primary" style="margin-top: 1rem; display: inline-flex;">
            <i class="bi bi-plus-lg"></i> Tambah Materi Pertama
        </a>
        @endif
    </div>
    @endif
</div>

{{-- TAB: TUGAS --}}
<div id="panel-tugas" class="tab-panel" style="display: none;">
    @if($course->assignments->count())
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        @foreach($course->assignments as $a)
        @php $submission = $user->isSiswa() ? $a->submissionForStudent($user->id) : null; @endphp
        <div style="background: white; border-radius: 14px; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                <div style="flex: 1;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 0.35rem;">{{ $a->title }}</h4>
                    <p style="font-size: 0.82rem; color: #64748b; margin-bottom: 0.5rem;">{{ Str::limit($a->description, 150) }}</p>
                    <div style="display: flex; gap: 1rem; font-size: 0.78rem; color: #94a3b8;">
                        <span><i class="bi bi-clock"></i> Deadline: <strong style="color: {{ $a->due_date->isPast() ? '#dc2626' : '#047857' }}">{{ $a->due_date->isoFormat('D MMM YYYY, HH:mm') }}</strong></span>
                        <span><i class="bi bi-award"></i> Nilai Maks: {{ $a->max_score }}</span>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    @if($user->isSiswa())
                        @if($submission)
                            <span class="badge badge-{{ $submission->status === 'graded' ? 'success' : 'warning' }}">
                                {{ $submission->status === 'graded' ? '✅ Nilai: ' . $submission->score : '⏳ Menunggu Penilaian' }}
                            </span>
                        @else
                            <a href="{{ route('lms.assignment.show', $a->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-upload"></i> Kumpulkan
                            </a>
                        @endif
                    @elseif($user->isGuru())
                        <a href="{{ route('lms.assignment.show', $a->id) }}" class="btn btn-outline btn-sm">
                            <i class="bi bi-people"></i> Lihat Pengumpulan
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align: center; padding: 3rem; background: white; border-radius: 16px; border: 1px solid #e2e8f0;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📝</div>
        <p style="color: #94a3b8;">Belum ada tugas yang diberikan.</p>
    </div>
    @endif
</div>

{{-- TAB: KUIS --}}
<div id="panel-kuis" class="tab-panel" style="display: none;">
    @if($course->quizzes->count())
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        @foreach($course->quizzes as $q)
        @php $attempt = $user->isSiswa() ? $q->attemptForStudent($user->id) : null; @endphp
        <div style="background: white; border-radius: 14px; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                <div style="flex: 1;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 0.35rem;">{{ $q->title }}</h4>
                    <p style="font-size: 0.82rem; color: #64748b; margin-bottom: 0.5rem;">{{ $q->description }}</p>
                    <div style="display: flex; gap: 1rem; font-size: 0.78rem; color: #94a3b8;">
                        <span><i class="bi bi-clock-history"></i> Durasi: <strong>{{ $q->duration_minutes }} menit</strong></span>
                        <span><i class="bi bi-patch-question"></i> {{ $q->questions->count() }} soal</span>
                        <span><i class="bi bi-award"></i> KKM: {{ $q->passing_score }}</span>
                        @if($q->due_date)
                        <span><i class="bi bi-calendar-x"></i> Deadline: {{ $q->due_date->isoFormat('D MMM YYYY') }}</span>
                        @endif
                    </div>
                </div>
                <div>
                    @if($user->isSiswa())
                        @if($attempt && $attempt->status === 'completed')
                            <a href="{{ route('lms.quiz.result', $attempt->id) }}" class="btn btn-outline btn-sm">
                                <i class="bi bi-bar-chart-fill"></i> Lihat Hasil
                            </a>
                        @elseif($q->due_date && $q->due_date->isPast())
                            <span class="badge badge-danger">Waktu Habis</span>
                        @else
                            <a href="{{ route('lms.quiz.start', $q->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-play-fill"></i> Mulai Kuis
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align: center; padding: 3rem; background: white; border-radius: 16px; border: 1px solid #e2e8f0;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">🧩</div>
        <p style="color: #94a3b8;">Belum ada kuis yang tersedia.</p>
    </div>
    @endif
</div>

{{-- TAB: PRESENSI --}}
<div id="panel-presensi" class="tab-panel" style="display: none;">
    <div style="background: white; border-radius: 16px; padding: 1.5rem; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 1rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
                <h4 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; margin-bottom: 0.25rem;">
                    <i class="bi bi-calendar-check-fill" style="color: #047857;"></i> Presensi Kehadiran Kelas
                </h4>
                <p style="font-size: 0.82rem; color: #64748b; margin: 0;">
                    Total Pertemuan Tercatat: <strong>{{ $course->attendances->count() }} Pertemuan</strong>
                </p>
            </div>
            <a href="{{ route('lms.attendance', $course->id) }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="bi bi-pencil-square"></i> {{ ($user->isGuru() && $course->teacher_id === $user->id) || $user->isAdmin() ? 'Kelola / Input Presensi' : 'Lihat Rekap Presensi Saya' }}
            </a>
        </div>

        @if($course->attendances->count())
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            @foreach($course->attendances->take(5) as $att)
            @php $myRec = $user->isSiswa() ? $att->records->where('student_id', $user->id)->first() : null; @endphp
            <div style="background: #f8fafc; border-radius: 10px; padding: 0.875rem 1.25rem; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-weight: 700; font-size: 0.875rem; color: #1e293b;">{{ $att->title }}</div>
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;"><i class="bi bi-calendar3"></i> {{ $att->date?->isoFormat('dddd, D MMMM YYYY') }}</div>
                </div>
                <div>
                    @if($user->isSiswa())
                        @if($myRec)
                        <span class="badge badge-{{ $myRec->status === 'hadir' ? 'emerald' : ($myRec->status === 'izin' ? 'warning' : ($myRec->status === 'sakit' ? 'info' : 'danger')) }}">
                            {{ strtoupper($myRec->status) }}
                        </span>
                        @else
                        <span class="badge badge-secondary">-</span>
                        @endif
                    @else
                        <span style="font-size: 0.78rem; font-weight: 600; color: #047857; background: #d1fae5; padding: 0.25rem 0.6rem; border-radius: 6px;">
                            {{ $att->records->where('status', 'hadir')->count() }} / {{ $att->records->count() }} Hadir
                        </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 2rem; color: #94a3b8;">
            <i class="bi bi-calendar-x" style="font-size: 2.5rem;"></i>
            <p style="margin-top: 0.5rem; font-size: 0.85rem;">Belum ada presensi diisi untuk kelas ini.</p>
        </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
function showTab(id) {
    document.querySelectorAll('.tab-panel').forEach(p => p.style.display = 'none');
    document.querySelectorAll('[id^="tab-"]').forEach(b => {
        b.style.background = 'white';
        b.style.color = '#64748b';
        b.style.borderColor = '#e2e8f0';
    });
    document.getElementById('panel-' + id).style.display = 'block';
    const btn = document.getElementById('tab-' + id);
    btn.style.background = '#047857';
    btn.style.color = 'white';
    btn.style.borderColor = '#047857';
}
showTab('materi');
</script>
@endsection
