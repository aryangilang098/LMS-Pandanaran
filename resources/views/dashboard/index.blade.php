@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
@php $user = auth()->user(); @endphp

{{-- ─── WELCOME BANNER ──────────────────────────────────────────────────── --}}
<div style="background: linear-gradient(135deg, #064e3b, #047857); border-radius: 20px; padding: 2rem; margin-bottom: 1.75rem; display: flex; align-items: center; justify-content: space-between; overflow: hidden; position: relative;">
    <div style="position: absolute; right: -50px; top: -50px; width: 250px; height: 250px; border-radius: 50%; background: rgba(255,255,255,0.05);"></div>
    <div style="position: absolute; right: 80px; bottom: -80px; width: 200px; height: 200px; border-radius: 50%; background: rgba(251,191,36,0.08);"></div>
    <div style="position: relative;">
        <div style="color: rgba(255,255,255,0.7); font-size: 0.82rem; margin-bottom: 0.35rem;">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</div>
        <h2 style="color: white; font-size: 1.5rem; font-weight: 800; margin-bottom: 0.35rem;">
            Assalamu'alaikum, {{ Str::words($user->name, 2) }}! 👋
        </h2>
        <p style="color: rgba(255,255,255,0.75); font-size: 0.875rem;">
            @if($user->isAdmin())    Selamat datang di panel administrasi sistem MA Sunan Pandanaran.
            @elseif($user->isGuru()) Semangat mengajar dan membimbing para santri hari ini!
            @elseif($user->isSiswa()) Semangat belajar! Raih prestasi terbaik untuk masa depan cerahmu.
            @else                    Pantau laporan keuangan madrasah dan verifikasi pembayaran masuk.
            @endif
        </p>
    </div>
    <div style="position: relative; text-align: right;">
        <div style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); border-radius: 14px; padding: 1rem 1.5rem; color: white;">
            <div style="font-size: 2rem;">
                @if($user->isAdmin()) 🛡️
                @elseif($user->isGuru()) 📚
                @elseif($user->isSiswa()) 🎓
                @else 💰
                @endif
            </div>
            <div style="font-size: 0.8rem; margin-top: 0.25rem; opacity: 0.8;">{{ ucfirst($user->role) }}</div>
        </div>
    </div>
</div>

{{-- ─── ADMIN DASHBOARD ─────────────────────────────────────────────────── --}}
@if($user->isAdmin())
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon si-emerald"><i class="bi bi-people-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $total_siswa }}</div>
            <div class="stat-label">Total Siswa</div>
            <div class="stat-sub">↑ Aktif Tahun 2025/2026</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="bi bi-person-badge-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $total_guru }}</div>
            <div class="stat-label">Tenaga Pendidik</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="bi bi-building-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $total_kelas }}</div>
            <div class="stat-label">Kelas / Rombel</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="bi bi-credit-card-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $pending_payments }}</div>
            <div class="stat-label">Pembayaran Pending</div>
            @if($pending_payments > 0)
            <a href="{{ route('payment.finance.index') }}"><div class="stat-sub">Verifikasi sekarang →</div></a>
            @endif
        </div>
    </div>
</div>
<div class="cards-grid-2">
    <div class="card">
        <div class="card-header">
            <h3>📰 Berita Terbaru</h3>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>Judul</th><th>Kategori</th><th>Tanggal</th></tr></thead>
                    <tbody>
                        @foreach($recent_posts as $post)
                        <tr>
                            <td style="max-width: 250px;"><div style="font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $post->title }}</div></td>
                            <td><span class="badge badge-{{ $post->category === 'prestasi' ? 'warning' : ($post->category === 'pengumuman' ? 'info' : 'emerald') }}">{{ ucfirst($post->category) }}</span></td>
                            <td style="font-size: 0.78rem; color: #94a3b8;">{{ $post->published_at?->isoFormat('D MMM YYYY') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>👤 Pengguna Terbaru</h3>
            <a href="{{ route('admin.users') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>Nama</th><th>Peran</th></tr></thead>
                    <tbody>
                        @foreach($recent_users as $u)
                        <tr>
                            <td><div style="font-weight: 600;">{{ $u->name }}</div><div style="font-size: 0.72rem; color: #94a3b8;">{{ $u->email }}</div></td>
                            <td><span class="badge badge-{{ $u->role === 'admin' ? 'danger' : ($u->role === 'guru' ? 'info' : ($u->role === 'bendahara' ? 'warning' : 'emerald')) }}">{{ ucfirst($u->role) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ─── GURU DASHBOARD ──────────────────────────────────────────────────── --}}
@if($user->isGuru())
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon si-emerald"><i class="bi bi-mortarboard-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $total_courses }}</div>
            <div class="stat-label">Kelas Diampu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="bi bi-award-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $avg_score }}</div>
            <div class="stat-label">Rata-Rata Nilai Siswa</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="bi bi-file-earmark-text-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $total_assignments }}</div>
            <div class="stat-label">Total Tugas Dibuat</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="bi bi-inbox-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $pending_submissions }}</div>
            <div class="stat-label">Tugas Belum Dinilai</div>
        </div>
    </div>
</div>
<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-header">
        <h3>📚 Kelas yang Saya Ampu</h3>
        <a href="{{ route('lms.courses') }}" class="btn btn-primary btn-sm"><i class="bi bi-grid-fill"></i> Lihat Semua</a>
    </div>
    <div class="card-body">
        @if($courses->count())
        <div class="course-cards-grid">
            @foreach($courses as $course)
            <a href="{{ route('lms.course.show', $course->id) }}" class="course-card">
                <div class="course-card-header" style="background: linear-gradient(135deg, {{ ['#047857,#059669','#1d4ed8,#3b82f6','#7c3aed,#8b5cf6','#b45309,#d97706'][($course->id - 1) % 4] }});">
                    <h3>{{ $course->subject->name }}</h3>
                </div>
                <div class="course-card-body">
                    <div class="course-meta">
                        <div class="course-meta-item"><i class="bi bi-building2"></i> {{ $course->schoolClass->name }}</div>
                        <div class="course-meta-item"><i class="bi bi-calendar-week"></i> {{ $course->day }} {{ $course->time_start }}–{{ $course->time_end }}</div>
                        <div class="course-meta-item"><i class="bi bi-door-open"></i> {{ $course->room }}</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 2rem; color: #94a3b8;">Belum ada kelas yang ditugaskan.</div>
        @endif
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3>📥 Tugas Baru Dikumpulkan</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Siswa</th><th>Tugas</th><th>Waktu</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($recent_submissions as $s)
                    <tr>
                        <td><div style="font-weight: 600;">{{ $s->student->name }}</div></td>
                        <td>{{ Str::limit($s->assignment->title, 40) }}</td>
                        <td style="font-size: 0.78rem; color: #94a3b8;">{{ $s->submitted_at->diffForHumans() }}</td>
                        <td><span class="badge badge-{{ $s->status === 'graded' ? 'success' : 'warning' }}">{{ ucfirst($s->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align: center; color: #94a3b8; padding: 2rem;">Belum ada tugas dikumpulkan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- ─── SISWA DASHBOARD ─────────────────────────────────────────────────── --}}
@if($user->isSiswa())
<div class="stats-grid">
    <div class="stat-card" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white;">
        <div class="stat-icon" style="background: rgba(255,255,255,0.2); color: white;"><i class="bi bi-award-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num" style="color: white; font-size: 1.8rem; font-weight: 800;">{{ $overall_avg }} <span style="font-size: 0.9rem; opacity: 0.9;">/ 100</span></div>
            <div class="stat-label" style="color: rgba(255,255,255,0.9); font-weight: 600;">Rata-Rata Nilai Saya</div>
            <div class="stat-sub" style="color: #fef08a; font-weight: 700;">Predikat: {{ $grade_predicate }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-emerald"><i class="bi bi-mortarboard-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $total_courses }}</div>
            <div class="stat-label">Mata Pelajaran</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="bi bi-cash-stack"></i></div>
        <div class="stat-body">
            <div class="stat-num">Rp {{ number_format($total_unpaid, 0, ',', '.') }}</div>
            <div class="stat-label">Tagihan Tertunggak ({{ $unpaid_bills->count() }})</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-purple"><i class="bi bi-clipboard2-check-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $recent_attempts->count() }}</div>
            <div class="stat-label">Kuis Selesai Dikerjakan</div>
        </div>
    </div>
</div>

{{-- Widget Breakdown Nilai Siswa --}}
<div class="card" style="margin-bottom: 1.25rem; background: #ffffff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
    <div class="card-header" style="border-bottom: 1px solid #f1f5f9; padding: 1rem 1.25rem;">
        <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
            <span>📈</span> Rincian Nilai & Capaian Belajar
        </h3>
        <span class="badge badge-emerald" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;">{{ $grade_predicate }}</span>
    </div>
    <div class="card-body" style="padding: 1.25rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">RATA-RATA TUGAS</div>
                    <div style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-top: 0.2rem;">{{ $assignment_avg }}</div>
                </div>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="bi bi-journal-check"></i>
                </div>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">RATA-RATA KUIS</div>
                    <div style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-top: 0.2rem;">{{ $quiz_avg }}</div>
                </div>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">RATA-RATA UTS</div>
                    <div style="font-size: 1.3rem; font-weight: 800; color: #047857; margin-top: 0.2rem;">{{ $uts_avg }}</div>
                </div>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #d1fae5; color: #047857; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="bi bi-file-earmark-diff-fill"></i>
                </div>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">RATA-RATA UAS</div>
                    <div style="font-size: 1.3rem; font-weight: 800; color: #1d4ed8; margin-top: 0.2rem;">{{ $uas_avg }}</div>
                </div>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #dbeafe; color: #1d4ed8; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="bi bi-award-fill"></i>
                </div>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">SKOR GABUNGAN</div>
                    <div style="font-size: 1.3rem; font-weight: 800; color: #059669; margin-top: 0.2rem;">{{ $overall_avg }}</div>
                </div>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #d1fae5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
        </div>
        <div>
            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 0.35rem;">
                <span>Kemajuan Akademik ({{ $overall_avg === '-' ? 'Belum ada nilai' : $overall_avg . '%' }})</span>
                <span>Target Minimum: 75</span>
            </div>
            <div style="width: 100%; height: 10px; background: #e2e8f0; border-radius: 999px; overflow: hidden;">
                <div style="width: {{ $overall_avg === '-' ? 0 : min(100, max(0, (float)$overall_avg)) }}%; height: 100%; background: linear-gradient(90deg, #10b981, #059669); border-radius: 999px; transition: width 0.5s ease;"></div>
            </div>
        </div>
    </div>
</div>

@if($unpaid_bills->count())
<div class="card" style="margin-bottom: 1.25rem; border-left: 4px solid #f59e0b;">
    <div class="card-header">
        <h3>⚠️ Tagihan Belum Lunas</h3>
        <a href="{{ route('payment.student.bills') }}" class="btn btn-gold btn-sm">Bayar Sekarang</a>
    </div>
    <div class="card-body" style="display: flex; flex-direction: column; gap: 0.75rem;">
        @foreach($unpaid_bills as $bill)
        <div class="bill-card">
            <div class="bill-icon" style="background: {{ $bill->status === 'pending' ? '#fef3c7' : '#fee2e2' }}; color: {{ $bill->status === 'pending' ? '#d97706' : '#dc2626' }};">
                <i class="bi bi-{{ $bill->status === 'pending' ? 'hourglass-split' : 'receipt' }}"></i>
            </div>
            <div class="bill-details">
                <h4>{{ $bill->title }}</h4>
                <div class="amount">Rp {{ number_format($bill->amount, 0, ',', '.') }}</div>
                <div class="due">Jatuh tempo: {{ $bill->due_date->isoFormat('D MMMM YYYY') }}</div>
            </div>
            <span class="badge badge-{{ $bill->status === 'pending' ? 'warning' : 'danger' }}">{{ ucfirst($bill->status) }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h3>📚 Kelas Aktif Saya</h3>
        <a href="{{ route('lms.courses') }}" class="btn btn-primary btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body">
        @if($courses->count())
        <div class="course-cards-grid">
            @foreach($courses as $course)
            <a href="{{ route('lms.course.show', $course->id) }}" class="course-card">
                <div class="course-card-header" style="background: linear-gradient(135deg, {{ ['#047857,#059669','#1d4ed8,#3b82f6','#7c3aed,#8b5cf6','#b45309,#d97706'][($course->id - 1) % 4] }});">
                    <h3>{{ $course->subject->name }}</h3>
                </div>
                <div class="course-card-body">
                    <div class="course-meta">
                        <div class="course-meta-item"><i class="bi bi-person-badge"></i> {{ Str::words($course->teacher->name, 2) }}</div>
                        <div class="course-meta-item"><i class="bi bi-calendar-week"></i> {{ $course->day }}, {{ $course->time_start }}</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 2rem; color: #94a3b8;">Tidak ada kelas aktif. Hubungi wali kelas Anda.</div>
        @endif
    </div>
</div>
@endif

{{-- ─── BENDAHARA DASHBOARD ─────────────────────────────────────────────── --}}
@if($user->isBendahara())
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $total_pending }}</div>
            <div class="stat-label">Menunggu Verifikasi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-emerald"><i class="bi bi-graph-up-arrow"></i></div>
        <div class="stat-body">
            <div class="stat-num">Rp {{ number_format($total_income_month, 0, ',', '.') }}</div>
            <div class="stat-label">Pemasukan Bulan Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $total_paid_bills }}</div>
            <div class="stat-label">Tagihan Lunas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-purple"><i class="bi bi-x-circle-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">{{ $total_unpaid_bills }}</div>
            <div class="stat-label">Tagihan Belum Bayar</div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3>🔔 Pembayaran Menunggu Verifikasi</h3>
        <a href="{{ route('payment.finance.index') }}" class="btn btn-primary btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Kode</th><th>Siswa</th><th>Jenis</th><th>Jumlah</th><th>Metode</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($pending_payments->take(5) as $p)
                    <tr>
                        <td style="font-size: 0.75rem; color: #94a3b8;">{{ $p->payment_code }}</td>
                        <td><div style="font-weight: 600;">{{ $p->bill->student->name }}</div></td>
                        <td>{{ $p->bill->feeType->name }}</td>
                        <td style="font-weight: 700; color: #047857;">Rp {{ number_format($p->amount_paid, 0, ',', '.') }}</td>
                        <td><span class="badge badge-info">{{ str_replace('_', ' ', $p->payment_method) }}</span></td>
                        <td>
                            <div style="display: flex; gap: 0.4rem;">
                                <form action="{{ route('payment.finance.verify', $p->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Verifikasi pembayaran ini?')"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <form action="{{ route('payment.finance.verify', $p->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pembayaran ini?')"><i class="bi bi-x-lg"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align: center; color: #94a3b8; padding: 2rem;">✅ Tidak ada pembayaran yang menunggu verifikasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection
