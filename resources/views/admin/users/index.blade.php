@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna')

@section('content')
{{-- Search & Filter --}}
<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form method="GET" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <input type="text" name="search" class="form-input" style="max-width: 300px;" placeholder="🔍 Cari nama, email, NISN..." value="{{ request('search') }}">
            <select name="role" class="form-input" style="width: auto;" onchange="this.form.submit()">
                <option value="">Semua Peran</option>
                @foreach(['admin' => '🛡️ Admin', 'guru' => '👨‍🏫 Guru', 'siswa' => '🎓 Siswa', 'bendahara' => '💰 Bendahara'] as $r => $label)
                <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Cari</button>
            <a href="{{ route('admin.users') }}" class="btn btn-outline btn-sm">Reset</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-gold btn-sm" style="margin-left: auto;"><i class="bi bi-person-plus-fill"></i> Tambah Pengguna</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>👤 Daftar Pengguna ({{ $users->total() }})</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>Nama</th><th>Email</th><th>Peran</th><th>Identitas</th><th>Kelas</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.6rem;">
                                <div style="width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, #047857, #059669); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; font-size: 0.875rem;">{{ $u->name }}</div>
                                    <div style="font-size: 0.7rem; color: #94a3b8;">{{ $u->gender === 'L' ? '♂ Laki-laki' : '♀ Perempuan' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size: 0.8rem; color: #64748b;">{{ $u->email }}</td>
                        <td>
                            <span class="badge badge-{{ $u->role === 'admin' ? 'danger' : ($u->role === 'guru' ? 'info' : ($u->role === 'bendahara' ? 'warning' : 'emerald')) }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td style="font-size: 0.8rem; color: #64748b;">{{ $u->identity_number ?? '-' }}</td>
                        <td style="font-size: 0.8rem;">{{ $u->schoolClass?->name ?? '-' }}</td>
                        <td>
                            <div style="display: flex; gap: 0.35rem;">
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-outline btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                @if($u->id !== auth()->id())
                                <form action="{{ route('admin.users.delete', $u->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pengguna {{ $u->name }}?')">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align: center; padding: 3rem; color: #94a3b8;">Tidak ada pengguna ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div style="padding: 1rem 1.5rem; display: flex; justify-content: center;">
            {{ $users->appends(request()->all())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
