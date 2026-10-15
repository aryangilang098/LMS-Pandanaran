@extends('layouts.app')

@section('title', 'Presensi Kehadiran - ' . $course->subject->name)
@section('page_title', 'Presensi Kehadiran')
@section('breadcrumb', $course->subject->name)

@section('content')
{{-- HEADER BANNER --}}
<div style="background: linear-gradient(135deg, #064e3b, #047857); border-radius: 20px; padding: 2rem; margin-bottom: 1.75rem; color: white;">
    <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="font-size: 0.78rem; color: rgba(255,255,255,0.65); margin-bottom: 0.4rem;">
                <a href="{{ route('lms.course.show', $course->id) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">
                    <i class="bi bi-arrow-left"></i> Kembali ke Detail Kelas {{ $course->subject->name }}
                </a>
            </div>
            <h2 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.5rem;">
                📋 Presensi Kehadiran {{ $course->subject->name }}
            </h2>
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; font-size: 0.82rem; color: rgba(255,255,255,0.85);">
                <span><i class="bi bi-building2"></i> Kelas: {{ $course->schoolClass->name }}</span>
                <span><i class="bi bi-person-badge"></i> Pengampu: {{ $course->teacher->name }}</span>
                <span><i class="bi bi-calendar-week"></i> {{ $course->day }}, {{ $course->time_start }} - {{ $course->time_end }}</span>
            </div>
        </div>
    </div>
</div>

{{-- STATISTIK RINGKASAN PRESENSI --}}
@php
    $totalPertemuan = $attendances->count();
    if ($user->isSiswa()) {
        $myRecords = \App\Models\AttendanceRecord::whereIn('attendance_id', $attendances->pluck('id'))
            ->where('student_id', $user->id)
            ->get();
        $countHadir = $myRecords->where('status', 'hadir')->count();
        $countIzin  = $myRecords->where('status', 'izin')->count();
        $countSakit = $myRecords->where('status', 'sakit')->count();
        $countAlpa  = $myRecords->where('status', 'alpa')->count();
        $persenHadir = $totalPertemuan > 0 ? round(($countHadir / $totalPertemuan) * 100, 1) : 0;
    }
@endphp

<div class="stats-grid" style="margin-bottom: 1.75rem;">
    <div class="stat-card">
        <div class="stat-icon si-emerald"><i class="bi bi-calendar-check-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $totalPertemuan }}</div>
            <div class="stat-label">Total Pertemuan Presensi</div>
        </div>
    </div>

    @if($user->isSiswa())
    <div class="stat-card" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white;">
        <div class="stat-icon" style="background: rgba(255,255,255,0.2); color: white;"><i class="bi bi-pie-chart-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num" style="color: white; font-size: 1.8rem; font-weight: 800;">{{ $persenHadir }}%</div>
            <div class="stat-label" style="color: rgba(255,255,255,0.9); font-weight: 600;">Persentase Kehadiran Saya</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="bi bi-person-check-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $countHadir }} <span style="font-size: 0.8rem; color: #64748b;">/ {{ $totalPertemuan }} H</span></div>
            <div class="stat-label">Hadir</div>
            <div class="stat-sub" style="font-size: 0.75rem;">Izin: {{ $countIzin }} | Sakit: {{ $countSakit }} | Alpa: {{ $countAlpa }}</div>
        </div>
    </div>
    @else
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="bi bi-people-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $students->count() }}</div>
            <div class="stat-label">Total Siswa Terdaftar</div>
        </div>
    </div>
    @endif
</div>

{{-- FORM INPUT PRESENSI BARU (KHUSUS GURU & ADMIN) --}}
@if(($user->isGuru() && $course->teacher_id === $user->id) || $user->isAdmin())
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header" style="background: #f8fafc;">
        <h3><i class="bi bi-plus-circle-fill" style="color: #047857;"></i> Input Presensi Pertemuan Baru</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('lms.attendance.store', $course->id) }}" method="POST">
            @csrf
            <div class="form-grid-2" style="margin-bottom: 1.25rem;">
                <div class="form-group">
                    <label class="form-label">Judul Pertemuan *</label>
                    <input type="text" name="title" class="form-input" required 
                           value="Pertemuan {{ $totalPertemuan + 1 }}" 
                           placeholder="Contoh: Pertemuan {{ $totalPertemuan + 1 }} - Pembahasan Bab 1">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Pertemuan *</label>
                    <input type="date" name="date" class="form-input" required value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                    <label class="form-label" style="margin: 0;">Daftar Kehadiran Siswa ({{ $students->count() }} Siswa)</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" onclick="setAllStatus('hadir')" class="btn btn-sm" style="background: #d1fae5; color: #047857; border: none; font-size: 0.75rem;">
                            Set Semua Hadir
                        </button>
                    </div>
                </div>

                <div class="table-wrapper" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                    <table>
                        <thead>
                            <tr style="background: #f1f5f9;">
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th style="text-align: center;">Status Kehadiran</th>
                                <th>Catatan / Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $index => $s)
                            <tr>
                                <td style="width: 40px; text-align: center;">{{ $index + 1 }}</td>
                                <td>
                                    <div style="font-weight: 700; color: #1e293b;">{{ $s->name }}</div>
                                </td>
                                <td style="font-size: 0.8rem; color: #64748b;">{{ $s->identity_number ?? '-' }}</td>
                                <td>
                                    <div style="display: flex; gap: 0.75rem; justify-content: center; align-items: center;">
                                        <label style="cursor: pointer; font-size: 0.82rem; font-weight: 600; color: #047857; display: flex; align-items: center; gap: 0.25rem;">
                                            <input type="radio" name="statuses[{{ $s->id }}]" value="hadir" class="radio-status-{{ $s->id }}" checked style="accent-color: #047857;"> Hadir (H)
                                        </label>
                                        <label style="cursor: pointer; font-size: 0.82rem; font-weight: 600; color: #d97706; display: flex; align-items: center; gap: 0.25rem;">
                                            <input type="radio" name="statuses[{{ $s->id }}]" value="izin" class="radio-status-{{ $s->id }}" style="accent-color: #d97706;"> Izin (I)
                                        </label>
                                        <label style="cursor: pointer; font-size: 0.82rem; font-weight: 600; color: #2563eb; display: flex; align-items: center; gap: 0.25rem;">
                                            <input type="radio" name="statuses[{{ $s->id }}]" value="sakit" class="radio-status-{{ $s->id }}" style="accent-color: #2563eb;"> Sakit (S)
                                        </label>
                                        <label style="cursor: pointer; font-size: 0.82rem; font-weight: 600; color: #dc2626; display: flex; align-items: center; gap: 0.25rem;">
                                            <input type="radio" name="statuses[{{ $s->id }}]" value="alpa" class="radio-status-{{ $s->id }}" style="accent-color: #dc2626;"> Alpa (A)
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="notes[{{ $s->id }}]" class="form-input" style="padding: 0.35rem 0.6rem; font-size: 0.8rem;" placeholder="Keterangan opsional...">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem; color: #94a3b8;">
                                    Belum ada siswa terdaftar di kelas ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($students->count() > 0)
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                <i class="bi bi-check-circle-fill me-1"></i> Simpan Presensi Pertemuan
            </button>
            @endif
        </form>
    </div>
</div>
@endif

{{-- RIWAYAT PRESENSI PERTEMUAN --}}
<div class="card">
    <div class="card-header">
        <h3><i class="bi bi-clock-history"></i> Riwayat & Rekapitulasi Presensi</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($attendances->count())
        <div style="display: flex; flex-direction: column; gap: 1rem; padding: 1.25rem;">
            @foreach($attendances as $att)
            @php
                $records = $att->records;
                $hCount = $records->where('status', 'hadir')->count();
                $iCount = $records->where('status', 'izin')->count();
                $sCount = $records->where('status', 'sakit')->count();
                $aCount = $records->where('status', 'alpa')->count();
                $myRecord = $user->isSiswa() ? $records->where('student_id', $user->id)->first() : null;
            @endphp
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                <div style="background: #f8fafc; padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <div style="font-weight: 800; font-size: 0.95rem; color: #1e293b;">{{ $att->title }}</div>
                        <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">
                            <i class="bi bi-calendar3"></i> {{ $att->date?->isoFormat('dddd, D MMMM YYYY') }}
                        </div>
                    </div>
                    <div>
                        @if($user->isSiswa())
                            @if($myRecord)
                                <span class="badge badge-{{ $myRecord->status === 'hadir' ? 'emerald' : ($myRecord->status === 'izin' ? 'warning' : ($myRecord->status === 'sakit' ? 'info' : 'danger')) }}" style="font-size: 0.82rem; padding: 0.4rem 0.85rem;">
                                    Status Saya: {{ strtoupper($myRecord->status) }}
                                </span>
                            @else
                                <span class="badge badge-secondary">Belum ada data</span>
                            @endif
                        @else
                            <div style="display: flex; gap: 0.4rem; font-size: 0.78rem;">
                                <span style="background: #d1fae5; color: #047857; padding: 0.25rem 0.6rem; border-radius: 6px; font-weight: 700;">Hadir: {{ $hCount }}</span>
                                <span style="background: #fef3c7; color: #b45309; padding: 0.25rem 0.6rem; border-radius: 6px; font-weight: 700;">Izin: {{ $iCount }}</span>
                                <span style="background: #dbeafe; color: #1d4ed8; padding: 0.25rem 0.6rem; border-radius: 6px; font-weight: 700;">Sakit: {{ $sCount }}</span>
                                <span style="background: #fee2e2; color: #dc2626; padding: 0.25rem 0.6rem; border-radius: 6px; font-weight: 700;">Alpa: {{ $aCount }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Detail Table for Teacher --}}
                @if(!$user->isSiswa())
                <div class="table-wrapper" style="max-height: 220px; overflow-y: auto;">
                    <table>
                        <thead>
                            <tr style="font-size: 0.78rem;">
                                <th>Siswa</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $r)
                            <tr>
                                <td style="font-size: 0.82rem; font-weight: 600;">{{ $r->student->name ?? 'Siswa' }}</td>
                                <td>
                                    <span class="badge badge-{{ $r->status === 'hadir' ? 'emerald' : ($r->status === 'izin' ? 'warning' : ($r->status === 'sakit' ? 'info' : 'danger')) }}" style="font-size: 0.72rem;">
                                        {{ ucfirst($r->status) }}
                                    </span>
                                </td>
                                <td style="font-size: 0.78rem; color: #64748b;">{{ $r->note ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 3rem; color: #94a3b8;">
            <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
            <p style="margin-top: 1rem; font-size: 0.875rem;">Belum ada riwayat presensi di kelas ini.</p>
        </div>
        @endif
    </div>
</div>

<script>
function setAllStatus(status) {
    document.querySelectorAll('input[type="radio"][value="' + status + '"]').forEach(r => r.checked = true);
}
</script>
@endsection
