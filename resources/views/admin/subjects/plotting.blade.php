@extends('layouts.app')

@section('title', 'Plotting Mata Pelajaran')
@section('page_title', 'Plotting Mata Pelajaran & Jadwal')

@section('content')
<div class="content-header" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-800); margin: 0;">Plotting Mata Pelajaran ke Kelas</h2>
        <p style="font-size: 0.85rem; color: var(--slate-500); margin-top: 0.25rem;">Atur penugasan mata pelajaran, guru pengampu, rombel kelas, serta jadwal pelajaran.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('admin.subjects') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-book-half"></i> Master Mapel
        </a>
        <a href="{{ route('admin.courses.monitor') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-binoculars-fill"></i> Pantau KBM Seluruh Kelas
        </a>
        <button type="button" class="btn btn-primary btn-sm" onclick="openCreatePlottingModal()">
            <i class="bi bi-plus-lg"></i> Tambah Plotting Baru
        </button>
    </div>
</div>

{{-- ─── FILTER KELAS / MAPEL ───────────────────────────────────────────── --}}
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form method="GET" action="{{ route('admin.courses.plotting') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) 120px; gap: 1rem; align-items: end;">
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--slate-600); margin-bottom: 0.35rem; display: block;">Filter Rombel / Kelas</label>
            <select name="class_id" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }} ({{ $c->students_count }} Siswa)
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--slate-600); margin-bottom: 0.35rem; display: block;">Filter Mata Pelajaran</label>
            <select name="subject_id" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Mata Pelajaran</option>
                @foreach($subjects as $sb)
                    <option value="{{ $sb->id }}" {{ request('subject_id') == $sb->id ? 'selected' : '' }}>
                        {{ $sb->name }} ({{ $sb->code }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <a href="{{ route('admin.courses.plotting') }}" class="btn btn-secondary" style="width: 100%; justify-content: center;">Reset</a>
        </div>
    </form>
</div>

{{-- ─── TABEL PLOTTING ─────────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 1rem; font-weight: 700; color: var(--slate-800); margin: 0;">
            Daftar Plotting Aktif ({{ $courses->count() }} Mata Pelajaran Terjadwal)
        </h3>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Kelas / Rombel</th>
                    <th>Mata Pelajaran</th>
                    <th>Tipe Mapel</th>
                    <th>Guru Pengampu</th>
                    <th>Jadwal & Ruang</th>
                    <th style="text-align: right; width: 170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $c)
                <tr>
                    <td>
                        <span class="badge" style="background: var(--emerald-50); color: var(--emerald-800); font-weight: 800; font-size: 0.85rem; padding: 6px 10px; border: 1px solid var(--emerald-200);">
                            <i class="bi bi-building"></i> {{ $c->schoolClass?->name ?? 'Semua Kelas' }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--slate-800); font-size: 0.95rem;">
                            {{ $c->subject?->name }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--slate-400); font-family: monospace;">
                            Kode: {{ $c->subject?->code }}
                        </div>
                    </td>
                    <td>
                        @if($c->subject?->is_elective)
                            <span class="badge badge-warning">
                                <i class="bi bi-star-fill"></i> Peminatan (Kls 11-12)
                            </span>
                        @else
                            <span class="badge badge-emerald">
                                <i class="bi bi-check-circle-fill"></i> Default / Wajib
                            </span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            <div style="width: 30px; height: 30px; border-radius: 8px; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: 0.85rem; color: var(--slate-800);">
                                    {{ $c->teacher?->name ?? 'Belum Ditentukan' }}
                                </div>
                                <div style="font-size: 0.72rem; color: var(--slate-400);">
                                    {{ $c->teacher?->email }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 0.82rem; font-weight: 600; color: var(--slate-700);">
                            <i class="bi bi-calendar3"></i> {{ $c->day ?? 'Fleksibel' }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--slate-500);">
                            <i class="bi bi-clock"></i> {{ $c->time_start && $c->time_end ? $c->time_start . ' - ' . $c->time_end : '-' }} 
                            @if($c->room) &bull; <i class="bi bi-geo-alt"></i> {{ $c->room }} @endif
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('lms.course.show', $c->id) }}" class="btn btn-info btn-sm" title="Pantau / Masuk ke Kelas LMS" style="padding: 0.35rem 0.65rem;">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="openEditPlottingModal({{ json_encode($c) }})" title="Edit Plotting & Jadwal">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <form action="{{ route('admin.courses.delete', $c->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus plotting mapel ini dari kelas? Materi dan tugas yang sudah ada mungkin terpengaruh.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Plotting">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: var(--slate-400);">
                        <i class="bi bi-diagram-3" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
                        Belum ada plotting mata pelajaran untuk kelas yang dipilih.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ─── MODAL CREATE/EDIT PLOTTING ───────────────────────────────────────── --}}
<div id="plottingModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: white; border-radius: 16px; width: 100%; max-width: 560px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); overflow: hidden; animation: popIn 0.2s ease;">
        <div style="padding: 1.25rem 1.5rem; background: var(--emerald-800); color: white; display: flex; justify-content: space-between; align-items: center;">
            <h4 id="plottingModalTitle" style="font-size: 1.05rem; font-weight: 700; margin: 0;">Plotting Mata Pelajaran Baru</h4>
            <button type="button" onclick="closePlottingModal()" style="background: none; border: none; color: white; font-size: 1.3rem; cursor: pointer;">&times;</button>
        </div>
        <form id="plottingForm" method="POST" action="{{ route('admin.courses.store') }}" style="padding: 1.5rem;">
            @csrf
            <div id="plottingMethodField"></div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.35rem; display: block;">Kelas / Rombel Target</label>
                    <select name="class_id" id="inputPlotClass" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->academic_year }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.35rem; display: block;">Mata Pelajaran</label>
                    <select name="subject_id" id="inputPlotSubject" class="form-control" required>
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($subjects as $sb)
                            <option value="{{ $sb->id }}">
                                [{{ $sb->is_elective ? 'Peminatan' : 'Wajib' }}] {{ $sb->name }} ({{ $sb->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.35rem; display: block;">Guru Pengampu</label>
                <select name="teacher_id" id="inputPlotTeacher" class="form-control" required>
                    <option value="">-- Pilih Guru Pengampu --</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}">{{ $t->name }} (NIP: {{ $t->identity_number ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem; margin-bottom: 1.5rem;">
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--slate-800); margin-bottom: 0.75rem;">Jadwal Pelajaran (Opsional)</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <div>
                        <label style="font-size: 0.75rem; color: var(--slate-600); margin-bottom: 0.25rem; display: block;">Hari</label>
                        <select name="day" id="inputPlotDay" class="form-control">
                            <option value="">-- Fleksibel --</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jum'at">Jum'at</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 0.75rem; color: var(--slate-600); margin-bottom: 0.25rem; display: block;">Ruangan</label>
                        <input type="text" name="room" id="inputPlotRoom" class="form-control" placeholder="Contoh: R-201 / Lab Komputer">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    <div>
                        <label style="font-size: 0.75rem; color: var(--slate-600); margin-bottom: 0.25rem; display: block;">Jam Mulai</label>
                        <input type="time" name="time_start" id="inputPlotTimeStart" class="form-control">
                    </div>
                    <div>
                        <label style="font-size: 0.75rem; color: var(--slate-600); margin-bottom: 0.25rem; display: block;">Jam Selesai</label>
                        <input type="time" name="time_end" id="inputPlotTimeEnd" class="form-control">
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closePlottingModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitPlotting">Simpan Plotting</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreatePlottingModal() {
    document.getElementById('plottingModalTitle').textContent = 'Tambah Plotting Mata Pelajaran Baru';
    document.getElementById('plottingForm').action = "{{ route('admin.courses.store') }}";
    document.getElementById('plottingMethodField').innerHTML = '';
    document.getElementById('inputPlotClass').value = '';
    document.getElementById('inputPlotSubject').value = '';
    document.getElementById('inputPlotTeacher').value = '';
    document.getElementById('inputPlotDay').value = '';
    document.getElementById('inputPlotRoom').value = '';
    document.getElementById('inputPlotTimeStart').value = '';
    document.getElementById('inputPlotTimeEnd').value = '';
    document.getElementById('plottingModal').style.display = 'flex';
}

function openEditPlottingModal(c) {
    document.getElementById('plottingModalTitle').textContent = 'Edit Plotting: ' + (c.subject ? c.subject.name : '');
    document.getElementById('plottingForm').action = "/admin/plotting-mapel/" + c.id;
    document.getElementById('plottingMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('inputPlotClass').value = c.class_id;
    document.getElementById('inputPlotSubject').value = c.subject_id;
    document.getElementById('inputPlotTeacher').value = c.teacher_id;
    document.getElementById('inputPlotDay').value = c.day || '';
    document.getElementById('inputPlotRoom').value = c.room || '';
    document.getElementById('inputPlotTimeStart').value = c.time_start || '';
    document.getElementById('inputPlotTimeEnd').value = c.time_end || '';
    document.getElementById('plottingModal').style.display = 'flex';
}

function closePlottingModal() {
    document.getElementById('plottingModal').style.display = 'none';
}

window.addEventListener('click', function(e) {
    const modal = document.getElementById('plottingModal');
    if (e.target === modal) {
        closePlottingModal();
    }
});
</script>
@endsection
