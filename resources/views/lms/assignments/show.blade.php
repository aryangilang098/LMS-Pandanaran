@extends('layouts.app')

@section('title', 'Detail Tugas: ' . $assignment->title)
@section('page_title', 'Detail Tugas')

@section('content')
@php $user = auth()->user(); @endphp

<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('lms.course.show', $assignment->course_id) }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Kelas</a>
</div>

<div style="background: linear-gradient(135deg, #1d4ed8, #3b82f6); border-radius: 20px; padding: 2rem; margin-bottom: 1.75rem; color: white;">
    <h2 style="font-size: 1.3rem; font-weight: 800; margin-bottom: 0.5rem;">📝 {{ $assignment->title }}</h2>
    <div style="display: flex; gap: 1.5rem; font-size: 0.82rem; color: rgba(255,255,255,0.8); flex-wrap: wrap;">
        <span><i class="bi bi-book"></i> {{ $assignment->course->subject->name }}</span>
        <span><i class="bi bi-clock-history"></i> Deadline: <strong style="color: {{ $assignment->due_date->isPast() ? '#fca5a5' : '#6ee7b7' }}">{{ $assignment->due_date->isoFormat('D MMMM YYYY, HH:mm') }}</strong></span>
        <span><i class="bi bi-award"></i> Nilai Maks: {{ $assignment->max_score }}</span>
        @if($assignment->due_date->isPast())
            <span style="background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.4); padding: 2px 10px; border-radius: 50px; color: #fca5a5;">⚠️ Waktu Habis</span>
        @endif
    </div>
</div>

{{-- Description --}}
@if($assignment->description)
<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-header"><h3>📋 Deskripsi & Instruksi Tugas</h3></div>
    <div class="card-body">
        <div style="line-height: 1.8; color: #334155; white-space: pre-line;">{{ $assignment->description }}</div>
    </div>
</div>
@endif

{{-- Student Submit Form --}}
@if($user->isSiswa())
    @if($mySubmission)
    <div class="card" style="margin-bottom: 1.25rem; border-left: 4px solid {{ $mySubmission->status === 'graded' ? '#10b981' : '#f59e0b' }};">
        <div class="card-header"><h3>📤 Status Pengumpulan Saya</h3></div>
        <div class="card-body">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8;">Waktu Dikumpulkan</div>
                    <div style="font-weight: 700;">{{ $mySubmission->submitted_at->isoFormat('D MMMM YYYY, HH:mm') }}</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8;">Status</div>
                    <span class="badge badge-{{ $mySubmission->status === 'graded' ? 'success' : 'warning' }}">{{ ucfirst($mySubmission->status) }}</span>
                </div>
                @if($mySubmission->score !== null)
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8;">Nilai</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: {{ $mySubmission->score >= 75 ? '#047857' : '#dc2626' }};">{{ $mySubmission->score }} / {{ $assignment->max_score }}</div>
                </div>
                @endif
            </div>
            @if($mySubmission->student_note)
            <div style="background: #f8fafc; border-radius: 10px; padding: 0.875rem; margin-top: 1rem; font-size: 0.85rem; color: #334155;">
                <strong>Catatan saya:</strong> {{ $mySubmission->student_note }}
            </div>
            @endif
            @if($mySubmission->teacher_feedback)
            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px; padding: 0.875rem; margin-top: 0.75rem; font-size: 0.85rem; color: #065f46;">
                <strong>💬 Feedback Guru:</strong> {{ $mySubmission->teacher_feedback }}
            </div>
            @endif
        </div>
    </div>
    @elseif(!$assignment->due_date->isPast())
    <div class="card">
        <div class="card-header"><h3>📤 Kumpulkan Tugas</h3></div>
        <div class="card-body">
            <form action="{{ route('lms.assignment.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Upload File Tugas (PDF/DOC/JPG, maks 20MB)</label>
                    <input type="file" name="file" class="form-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan Tambahan untuk Guru</label>
                    <textarea name="student_note" class="form-input" rows="3" placeholder="Tulis catatan atau keterangan tambahan..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem;" onclick="return confirm('Kumpulkan tugas sekarang? Pengumpulan tidak dapat diulang.')">
                    <i class="bi bi-upload"></i> Kumpulkan Tugas
                </button>
            </form>
        </div>
    </div>
    @else
    <div style="background: #fee2e2; border-radius: 14px; padding: 1.5rem; text-align: center; color: #991b1b;">
        <div style="font-size: 2rem; margin-bottom: 0.5rem;">⏰</div>
        <strong>Waktu pengumpulan telah berakhir.</strong>
    </div>
    @endif
@endif

{{-- Teacher: View Submissions --}}
@if($user->isGuru() || $user->isAdmin())
<div class="card">
    <div class="card-header">
        <h3>📋 Daftar Pengumpulan Siswa ({{ $assignment->submissions->count() }})</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <table>
            <thead><tr><th>Siswa</th><th>Waktu Kumpul</th><th>File</th><th>Status</th><th>Nilai</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($assignment->submissions as $s)
                <tr>
                    <td style="font-weight: 700;">{{ $s->student->name }}</td>
                    <td style="font-size: 0.78rem; color: #64748b;">{{ $s->submitted_at->isoFormat('D MMM YYYY HH:mm') }}</td>
                    <td>
                        @if($s->file_path)
                        <a href="{{ Storage::url($s->file_path) }}" class="btn btn-outline btn-sm" target="_blank"><i class="bi bi-download"></i></a>
                        @else <span style="color: #94a3b8;">-</span> @endif
                    </td>
                    <td><span class="badge badge-{{ $s->status === 'graded' ? 'success' : 'warning' }}">{{ ucfirst($s->status) }}</span></td>
                    <td style="font-weight: 800; color: #047857;">{{ $s->score !== null ? $s->score : '-' }}</td>
                    <td>
                        <form action="{{ route('lms.assignment.grade', $s->id) }}" method="POST" style="display: flex; gap: 0.35rem; align-items: center;">
                            @csrf
                            <input type="number" name="score" class="form-input" style="width: 70px; padding: 0.35rem 0.5rem;" min="0" max="{{ $assignment->max_score }}" value="{{ $s->score }}" placeholder="0-{{ $assignment->max_score }}">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save"></i> Simpan</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center; padding: 2rem; color: #94a3b8;">Belum ada siswa yang mengumpulkan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
