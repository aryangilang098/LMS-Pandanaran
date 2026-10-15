@extends('layouts.app')

@section('title', 'Kartu Ujian Digital')
@section('page_title', 'Kartu Peserta Ujian (UTS & UAS)')
@section('breadcrumb', 'Kartu Ujian')

@section('content')
<!-- Print Styles -->
<style>
@media print {
    body { background: white !important; padding: 0 !important; color: black !important; }
    .sidebar, .topbar, .top-controls, .alert, .no-print { display: none !important; }
    .main-content { margin-left: 0 !important; padding: 0 !important; }
    .page-content { padding: 0 !important; }
    .exam-card-print-area {
        box-shadow: none !important; border: 2px solid #000 !important;
        margin: 0 auto !important; width: 100% !important; max-width: 800px !important;
    }
}
</style>

<!-- Top Controls Bar -->
<div class="card no-print" style="margin-bottom: 1.5rem;">
    <div class="card-body" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <a href="{{ route('exam_cards.student', ['type' => 'uts']) }}" 
               class="btn {{ $examType === 'uts' ? 'btn-primary' : 'btn-outline' }}">
                <i class="bi bi-file-earmark-text-fill"></i> Kartu Ujian UTS
            </a>
            <a href="{{ route('exam_cards.student', ['type' => 'uas']) }}" 
               class="btn {{ $examType === 'uas' ? 'btn-primary' : 'btn-outline' }}">
                <i class="bi bi-award-fill"></i> Kartu Ujian UAS
            </a>
        </div>

        <div style="display: flex; align-items: center; gap: 0.75rem;">
            @if($card->status === 'approved')
                <span class="badge badge-emerald" style="font-size: 0.8rem; padding: 6px 14px;">
                    <i class="bi bi-check-circle-fill"></i> Status: DISETUJUI ADMIN
                </span>
                <button onclick="window.print()" class="btn btn-gold">
                    <i class="bi bi-printer-fill"></i> Cetak / Unduh PDF
                </button>
            @elseif($card->status === 'pending')
                <span class="badge badge-warning" style="font-size: 0.8rem; padding: 6px 14px;">
                    <i class="bi bi-clock-fill"></i> MENUNGGU VERIFIKASI ADMIN
                </span>
                <button onclick="alert('Kartu Ujian Anda sedang dalam proses verifikasi panitia. Tombol cetak akan aktif setelah disetujui Admin.')" class="btn btn-outline" style="opacity: 0.7;">
                    <i class="bi bi-info-circle"></i> Menunggu Verifikasi
                </button>
            @else
                <span class="badge badge-danger" style="font-size: 0.8rem; padding: 6px 14px;">
                    <i class="bi bi-x-circle-fill"></i> Status: DITANGGUHKAN
                </span>
            @endif
        </div>
    </div>
</div>

<!-- Exam Card Container -->
<div class="card exam-card-print-area" style="max-width: 850px; margin: 0 auto; border: 2px solid #047857; border-radius: 16px; overflow: hidden; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
    
    <!-- Card Header / Kop -->
    <div style="background: linear-gradient(135deg, #064e3b, #047857); color: white; padding: 1.5rem 2rem; border-bottom: 3px solid #f59e0b; position: relative;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 54px; height: 54px; border-radius: 12px; background: linear-gradient(135deg, #f59e0b, #d97706); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.6rem; color: white; box-shadow: 0 4px 14px rgba(0,0,0,0.2); flex-shrink: 0;">
                    M
                </div>
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 800; letter-spacing: 0.5px; margin: 0; line-height: 1.2;">MADRASAH ALIYAH SUNAN PANDANARAN</h2>
                    <div style="font-size: 0.85rem; opacity: 0.95; margin-top: 2px; font-weight: 700;">KARTU PESERTA UJIAN</div>
                </div>
            </div>

            <div style="text-align: right;">
                <div style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); padding: 4px 12px; border-radius: 50px; display: inline-block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                    {{ strtoupper($examType) }} {{ strtoupper($semester) }} {{ $academicYear }}
                </div>
            </div>
        </div>
    </div>

    <!-- Student Info Section -->
    <div style="padding: 1.75rem 2rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
        <div style="display: flex; align-items: center; gap: 1.75rem; flex-wrap: wrap;">
            


            <!-- Detail Grid -->
            <div style="flex: 1; min-width: 250px;">
                <table style="width: 100%; border: none; font-size: 0.88rem;">
                    <tr>
                        <td style="padding: 4px 0; width: 140px; color: #64748b; font-weight: 600;">Nama Peserta</td>
                        <td style="padding: 4px 0; font-weight: 800; color: #1e293b;">: {{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; font-weight: 600;">NISN / No. Induk</td>
                        <td style="padding: 4px 0; font-weight: 700; color: #334155;">: {{ $user->identity_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Kelas / Rombel</td>
                        <td style="padding: 4px 0; font-weight: 700; color: #334155;">: {{ $user->class ? $user->class->name : 'Belum Ditentukan' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; font-weight: 600;">No. Peserta Ujian</td>
                        <td style="padding: 4px 0; font-weight: 800; color: #047857;">: {{ $card->exam_number }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Ruang Ujian</td>
                        <td style="padding: 4px 0; font-weight: 700; color: #1e293b;">: {{ $card->room_number }}</td>
                    </tr>
                </table>
            </div>



        </div>
    </div>

    <!-- Table of Subjects / Schedule -->
    <div style="padding: 1.5rem 2rem;">
        <h4 style="font-size: 0.9rem; font-weight: 800; color: #1e293b; margin-bottom: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">
            📋 Jadwal Mata Pelajaran Diuji
        </h4>

        <div class="table-wrapper" style="border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden;">
            <table style="width: 100%; margin: 0;">
                <thead>
                    <tr style="background: #064e3b; color: white;">
                        <th style="color: white; padding: 0.6rem 1rem;">No</th>
                        <th style="color: white; padding: 0.6rem 1rem;">Mata Pelajaran</th>
                        <th style="color: white; padding: 0.6rem 1rem;">Guru Pengampu</th>
                        <th style="color: white; padding: 0.6rem 1rem;">Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $idx => $course)
                    <tr>
                        <td style="font-weight: 700; width: 40px; text-align: center;">{{ $idx + 1 }}</td>
                        <td style="font-weight: 700; color: #1e293b;">{{ $course->name }}</td>
                        <td style="color: #475569;">{{ $course->teacher ? $course->teacher->name : '-' }}</td>
                        <td>
                            <span class="badge badge-emerald"><i class="bi bi-check2"></i> Siap Diuji</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8; padding: 1.5rem;">
                            Belum ada jadwal kelas yang dikonfigurasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer Seal & Signature -->
    <div style="padding: 1.25rem 2rem 1.75rem; background: #fff; border-top: 1px solid #f1f5f9; display: flex; align-items: flex-end; justify-content: space-between;">
        <div style="font-size: 0.72rem; color: #64748b; max-width: 340px; line-height: 1.5;">
            <strong>Catatan Peserta:</strong><br>
            - Kartu Ujian ini wajib dibawa dan ditunjukkan setiap mengikuti sesi ujian.<br>
            - Peserta wajib hadir 15 menit sebelum ujian dimulai di ruang ujian terdaftar.
        </div>

        <div style="text-align: center;">
            <div style="font-size: 0.75rem; color: #475569;">Sleman, {{ date('d F Y') }}</div>
            <div style="font-size: 0.78rem; font-weight: 700; color: #1e293b;">Ketua Panitia Ujian</div>
            <div style="height: 45px; display: flex; align-items: center; justify-content: center; color: #047857; font-weight: 800; font-size: 1.1rem; opacity: 0.8;">
                [ STEMPEL RESMI ]
            </div>
            <div style="font-size: 0.82rem; font-weight: 800; color: #064e3b; text-decoration: underline;">Drs. H. Ahmad Dahlan, M.Pd.</div>
            <div style="font-size: 0.68rem; color: #64748b;">NIP. 19750812 200312 1 002</div>
        </div>
    </div>

</div>
@endsection
