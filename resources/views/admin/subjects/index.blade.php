@extends('layouts.app')

@section('title', 'Master Mata Pelajaran')
@section('page_title', 'Master Mata Pelajaran')

@section('content')
<div class="content-header" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-800); margin: 0;">Master Mata Pelajaran</h2>
        <p style="font-size: 0.85rem; color: var(--slate-500); margin-top: 0.25rem;">Kelola kurikulum, mata pelajaran default (kelas 10), dan peminatan (kelas 11-12).</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('admin.courses.plotting') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-diagram-3-fill"></i> Plotting ke Kelas
        </a>
        <button type="button" class="btn btn-primary btn-sm" onclick="openCreateModal()">
            <i class="bi bi-plus-lg"></i> Tambah Mapel Baru
        </button>
    </div>
</div>

{{-- ─── FILTER SECTION ─────────────────────────────────────────────────── --}}
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form method="GET" action="{{ route('admin.subjects') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) 120px; gap: 1rem; align-items: end;">
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--slate-600); margin-bottom: 0.35rem; display: block;">Kategori</label>
            <select name="category" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="keagamaan" {{ request('category') === 'keagamaan' ? 'selected' : '' }}>Keagamaan</option>
                <option value="umum" {{ request('category') === 'umum' ? 'selected' : '' }}>Umum</option>
                <option value="peminatan" {{ request('category') === 'peminatan' ? 'selected' : '' }}>Peminatan</option>
            </select>
        </div>
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--slate-600); margin-bottom: 0.35rem; display: block;">Tingkat Kelas</label>
            <select name="grade_level" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Tingkat</option>
                <option value="10" {{ request('grade_level') === '10' ? 'selected' : '' }}>Kelas 10 (Default)</option>
                <option value="11" {{ request('grade_level') === '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('grade_level') === '12' ? 'selected' : '' }}>Kelas 12</option>
                <option value="11-12" {{ request('grade_level') === '11-12' ? 'selected' : '' }}>Kelas 11 & 12</option>
                <option value="all" {{ request('grade_level') === 'all' ? 'selected' : '' }}>Semua Kelas (10-12)</option>
            </select>
        </div>
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--slate-600); margin-bottom: 0.35rem; display: block;">Tipe Mapel</label>
            <select name="type" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Tipe</option>
                <option value="required" {{ request('type') === 'required' ? 'selected' : '' }}>Wajib / Default</option>
                <option value="elective" {{ request('type') === 'elective' ? 'selected' : '' }}>Peminatan (Pilihan Siswa)</option>
            </select>
        </div>
        <div>
            <a href="{{ route('admin.subjects') }}" class="btn btn-secondary" style="width: 100%; justify-content: center;">Reset</a>
        </div>
    </form>
</div>

{{-- ─── DAFTAR MAPEL ───────────────────────────────────────────────────── --}}
<div class="card">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 100px;">Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Kategori</th>
                    <th>Tingkat Kelas</th>
                    <th>Sifat / Tipe</th>
                    <th style="text-align: center;">Di-plot di Kelas</th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $s)
                <tr>
                    <td>
                        <span style="font-family: monospace; font-weight: 700; background: #f1f5f9; padding: 4px 8px; border-radius: 6px; color: var(--slate-700);">
                            {{ $s->code }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--slate-800);">{{ $s->name }}</div>
                    </td>
                    <td>
                        @if($s->category === 'keagamaan')
                            <span class="badge badge-emerald">🕌 Keagamaan</span>
                        @elseif($s->category === 'peminatan')
                            <span class="badge badge-warning">🎯 Peminatan</span>
                        @else
                            <span class="badge badge-info">📘 Umum</span>
                        @endif
                    </td>
                    <td>
                        @if($s->grade_level === '10')
                            <span class="badge" style="background: #e0e7ff; color: #4338ca; font-weight: 700;">Kelas 10 (Default)</span>
                        @elseif($s->grade_level === '11')
                            <span class="badge" style="background: #fef3c7; color: #b45309; font-weight: 700;">Kelas 11</span>
                        @elseif($s->grade_level === '12')
                            <span class="badge" style="background: #fce7f3; color: #be185d; font-weight: 700;">Kelas 12</span>
                        @elseif($s->grade_level === '11-12')
                            <span class="badge" style="background: #f3e8ff; color: #7e22ce; font-weight: 700;">Kelas 11 & 12</span>
                        @else
                            <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 700;">Semua Kelas</span>
                        @endif
                    </td>
                    <td>
                        @if($s->is_elective)
                            <span class="badge badge-warning" style="display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-star-fill"></i> Peminatan (Pilihan Siswa)
                            </span>
                        @else
                            <span class="badge badge-emerald" style="display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-check-circle-fill"></i> Wajib / Default
                            </span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span class="badge" style="background: #e2e8f0; color: #334155; font-weight: 700;">
                            {{ $s->courses_count }} Rombel
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <button type="button" class="btn btn-secondary btn-sm" 
                            onclick="openEditModal({{ json_encode($s) }})" title="Edit">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <form action="{{ route('admin.subjects.delete', $s->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus mata pelajaran {{ $s->name }}? Plotting kelas terkait juga akan terhapus.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2.5rem; color: var(--slate-400);">
                        <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                        Belum ada data mata pelajaran yang sesuai filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ─── MODAL CREATE/EDIT ───────────────────────────────────────────────── --}}
<div id="subjectModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: white; border-radius: 16px; width: 100%; max-width: 520px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); overflow: hidden; animation: popIn 0.2s ease;">
        <div style="padding: 1.25rem 1.5rem; background: var(--emerald-800); color: white; display: flex; justify-content: space-between; align-items: center;">
            <h4 id="modalTitle" style="font-size: 1.05rem; font-weight: 700; margin: 0;">Tambah Mata Pelajaran</h4>
            <button type="button" onclick="closeSubjectModal()" style="background: none; border: none; color: white; font-size: 1.3rem; cursor: pointer;">&times;</button>
        </div>
        <form id="subjectForm" method="POST" action="{{ route('admin.subjects.store') }}" style="padding: 1.5rem;">
            @csrf
            <div id="methodField"></div>

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.35rem; display: block;">Kode Mapel (Misal: MAT-10, BIO-XI, TAHFIDZ)</label>
                <input type="text" name="code" id="inputCode" required class="form-control" placeholder="Contoh: BIO-11" style="text-transform: uppercase;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.35rem; display: block;">Nama Mata Pelajaran</label>
                <input type="text" name="name" id="inputName" required class="form-control" placeholder="Contoh: Biologi Lanjutan">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.35rem; display: block;">Kategori</label>
                    <select name="category" id="inputCategory" class="form-control" required>
                        <option value="umum">Umum</option>
                        <option value="keagamaan">Keagamaan</option>
                        <option value="peminatan">Peminatan</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.35rem; display: block;">Tingkat Kelas</label>
                    <select name="grade_level" id="inputGradeLevel" class="form-control" required>
                        <option value="10">Kelas 10 (Default)</option>
                        <option value="11">Kelas 11</option>
                        <option value="12">Kelas 12</option>
                        <option value="11-12">Kelas 11 & 12</option>
                        <option value="all">Semua Kelas</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem; background: #f8fafc; padding: 1rem; border-radius: 10px; border: 1px solid #e2e8f0;">
                <label style="font-size: 0.82rem; font-weight: 700; color: var(--slate-800); margin-bottom: 0.5rem; display: block;">Sifat Mata Pelajaran:</label>
                <div style="display: flex; gap: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                        <input type="radio" name="is_elective" id="radioRequired" value="0" checked>
                        <span>Wajib / Default (Semua siswa mengikuti)</span>
                    </label>
                </div>
                <div style="margin-top: 0.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                        <input type="radio" name="is_elective" id="radioElective" value="1">
                        <span>Peminatan (Khusus kelas 11-12 dapat dipilih siswa)</span>
                    </label>
                </div>
                <p style="font-size: 0.72rem; color: var(--slate-500); margin-top: 0.5rem; margin-bottom: 0;">
                    *Siswa kelas 10 otomatis menerima mapel default/wajib. Mapel peminatan dapat dipilih langsung oleh siswa setelah beranjak ke kelas 11-12.
                </p>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeSubjectModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitModal">Simpan Mata Pelajaran</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Mata Pelajaran Baru';
    document.getElementById('subjectForm').action = "{{ route('admin.subjects.store') }}";
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('inputCode').value = '';
    document.getElementById('inputName').value = '';
    document.getElementById('inputCategory').value = 'umum';
    document.getElementById('inputGradeLevel').value = '10';
    document.getElementById('radioRequired').checked = true;
    document.getElementById('subjectModal').style.display = 'flex';
}

function openEditModal(subject) {
    document.getElementById('modalTitle').textContent = 'Edit Mata Pelajaran: ' + subject.name;
    document.getElementById('subjectForm').action = "/admin/mata-pelajaran/" + subject.id;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('inputCode').value = subject.code;
    document.getElementById('inputName').value = subject.name;
    document.getElementById('inputCategory').value = subject.category;
    document.getElementById('inputGradeLevel').value = subject.grade_level;
    if (subject.is_elective) {
        document.getElementById('radioElective').checked = true;
    } else {
        document.getElementById('radioRequired').checked = true;
    }
    document.getElementById('subjectModal').style.display = 'flex';
}

function closeSubjectModal() {
    document.getElementById('subjectModal').style.display = 'none';
}

window.addEventListener('click', function(e) {
    const modal = document.getElementById('subjectModal');
    if (e.target === modal) {
        closeSubjectModal();
    }
});
</script>
@endsection
