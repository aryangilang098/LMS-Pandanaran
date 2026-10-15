@extends('layouts.app')

@section('title', 'Manajemen Kartu Ujian')
@section('page_title', 'Manajemen Kartu Ujian (UTS & UAS)')
@section('breadcrumb', 'Kartu Ujian Admin')

@section('content')

<!-- Bulk Generation & Action Header -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h3>Generasi Massal & Filter Kartu Ujian</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.exam_cards.generate') }}" method="POST" style="background: var(--slate-50); border: 1px solid var(--slate-200); padding: 1.25rem; border-radius: 12px; margin-bottom: 1.5rem;">
            @csrf
            <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--slate-800); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="bi bi-layers-fill" style="color: var(--emerald-600);"></i> Generasi Kartu Ujian Massal per Kelas
            </h4>
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">Pilih Kelas Rombel</label>
                    <select name="class_id" class="form-input" required>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->students_count }} Siswa)</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Ujian & Tahun</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <select name="exam_type" class="form-input">
                            <option value="uts">UTS (Tengah Semester)</option>
                            <option value="uas">UAS (Akhir Semester)</option>
                        </select>
                        <input type="text" name="academic_year" class="form-input" value="2026/2027" placeholder="T.A.">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Format Ruang Ujian Default</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" name="room_prefix" class="form-input" value="Ruang 04 - Gedung B" required placeholder="Contoh: Ruang 01">
                        <input type="hidden" name="semester" value="ganjil">
                        <button type="submit" class="btn btn-primary" style="white-space: nowrap;">
                            <i class="bi bi-magic"></i> Generate Massal
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Filter & Search Bar -->
        <form action="{{ route('admin.exam_cards') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 180px;">
                <label class="form-label">Filter Kelas</label>
                <select name="class_id" class="form-input" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 150px;">
                <label class="form-label">Jenis Ujian</label>
                <select name="type" class="form-input" onchange="this.form.submit()">
                    <option value="uts" {{ $examType === 'uts' ? 'selected' : '' }}>UTS</option>
                    <option value="uas" {{ $examType === 'uas' ? 'selected' : '' }}>UAS</option>
                </select>
            </div>

            <div style="flex: 2; min-width: 200px;">
                <label class="form-label">Cari Nama Siswa / NISN</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text" name="search" class="form-input" value="{{ $search }}" placeholder="Ketik nama atau NISN...">
                    <button type="submit" class="btn btn-outline"><i class="bi bi-search"></i> Cari</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table of Students & Exam Cards -->
<div class="card">
    <div class="card-header">
        <h3>🎓 Daftar Kartu Ujian Siswa ({{ $students->total() }})</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa & Kelas</th>
                        <th>NISN</th>
                        <th>No. Peserta Ujian</th>
                        <th>Ruang Ujian</th>
                        <th>Status Kartu</th>
                        <th>Aksi / Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $idx => $student)
                    @php
                        $card = $student->examCards->first();
                    @endphp
                    <tr>
                        <td style="font-size: 0.8rem; color: var(--slate-400);">{{ $students->firstItem() + $idx }}</td>
                        <td>
                            <div style="font-weight: 700; font-size: 0.88rem; color: var(--slate-800);">{{ $student->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--slate-500);">Kelas: {{ $student->class ? $student->class->name : '-' }}</div>
                        </td>
                        <td style="font-size: 0.85rem; font-weight: 600; color: var(--slate-700);">{{ $student->identity_number ?? '-' }}</td>
                        <td>
                            @if($card)
                                <span style="font-weight: 800; color: var(--emerald-700); font-size: 0.85rem;">{{ $card->exam_number }}</span>
                            @else
                                <span class="badge badge-warning">Belum Ada</span>
                            @endif
                        </td>
                        <td style="font-size: 0.85rem; font-weight: 600; color: var(--slate-700);">
                            {{ $card ? $card->room_number : '-' }}
                        </td>
                        <td>
                            @if($card)
                                @if($card->status === 'approved')
                                    <span class="badge badge-success"><i class="bi bi-check-circle-fill"></i> Disetujui</span>
                                @elseif($card->status === 'pending')
                                    <span class="badge badge-warning"><i class="bi bi-clock-fill"></i> Menunggu</span>
                                @else
                                    <span class="badge badge-danger"><i class="bi bi-x-circle-fill"></i> Ditangguhkan</span>
                                @endif
                            @else
                                <span class="badge badge-warning">Draft</span>
                            @endif
                        </td>
                        <td>
                            <!-- Modal Button / Quick Edit Form Inline -->
                            <details style="position: relative; display: inline-block;">
                                <summary class="btn btn-outline btn-sm" style="cursor: pointer; list-style: none;">
                                    <i class="bi bi-pencil-square"></i> Atur Kartu
                                </summary>
                                <div style="position: absolute; right: 0; top: 100%; width: 320px; background: white; border: 1px solid var(--slate-200); box-shadow: 0 12px 30px rgba(0,0,0,0.15); border-radius: 12px; padding: 1.25rem; z-index: 100; margin-top: 5px;">
                                    <form action="{{ route('admin.exam_cards.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $student->id }}">
                                        <input type="hidden" name="exam_type" value="{{ $examType }}">
                                        <input type="hidden" name="academic_year" value="2026/2027">
                                        <input type="hidden" name="semester" value="ganjil">

                                        <h4 style="font-size: 0.85rem; font-weight: 700; margin-bottom: 0.75rem;">Edit Kartu: {{ $student->name }}</h4>

                                        <div class="form-group" style="margin-bottom: 0.75rem;">
                                            <label class="form-label" style="font-size: 0.75rem;">No. Peserta Ujian</label>
                                            <input type="text" name="exam_number" class="form-input" value="{{ $card ? $card->exam_number : strtoupper($examType).'-2026-'.str_pad($student->id, 4, '0', STR_PAD_LEFT) }}" required>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0.75rem;">
                                            <label class="form-label" style="font-size: 0.75rem;">Ruang Ujian</label>
                                            <input type="text" name="room_number" class="form-input" value="{{ $card ? $card->room_number : 'Ruang 04 - R102' }}" required>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 1rem;">
                                            <label class="form-label" style="font-size: 0.75rem;">Status Kartu Ujian</label>
                                            <select name="status" class="form-input">
                                                <option value="approved" {{ ($card && $card->status === 'approved') ? 'selected' : '' }}>Diizinkan / Approved</option>
                                                <option value="pending" {{ ($card && $card->status === 'pending') ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                                <option value="suspended" {{ ($card && $card->status === 'suspended') ? 'selected' : '' }}>Ditangguhkan / Suspended</option>
                                            </select>
                                        </div>

                                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check2"></i> Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </details>

                            @if($card)
                            <form action="{{ route('admin.exam_cards.delete', $card->id) }}" method="POST" style="display: inline-block; margin-left: 4px;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kartu ujian untuk {{ addslashes($student->name) }}?')" title="Hapus Kartu">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--slate-400); padding: 3rem;">
                            <i class="bi bi-file-earmark-x" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                            Tidak ada data siswa / kartu ujian ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div style="padding: 1rem 1.5rem; display: flex; justify-content: center;">
            {{ $students->appends(request()->all())->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
