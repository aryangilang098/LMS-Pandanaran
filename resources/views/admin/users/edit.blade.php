@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('page_title', 'Edit Pengguna')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h3>✏️ Edit Pengguna: {{ $user->name }}</h3>
            <a href="{{ route('admin.users') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-grid-2">
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-input" required value="{{ old('name', $user->name) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" required value="{{ old('email', $user->email) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-input" minlength="8" placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Peran</label>
                        <select name="role" class="form-input" required>
                            @foreach(['siswa','guru','bendahara','admin'] as $r)
                            <option value="{{ $r }}" {{ $user->role === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-input">
                            <option value="L" {{ $user->gender === 'L' ? 'selected' : '' }}>♂ Laki-laki</option>
                            <option value="P" {{ $user->gender === 'P' ? 'selected' : '' }}>♀ Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">NISN / NIP</label>
                        <input type="text" name="identity_number" class="form-input" value="{{ old('identity_number', $user->identity_number) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kelas</label>
                        <select name="class_id" class="form-input">
                            <option value="">-- Tanpa Kelas --</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $user->class_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-input" rows="2">{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem;">
                    <i class="bi bi-save-fill"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
