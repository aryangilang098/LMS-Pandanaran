@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')
@section('page_title', 'Tambah Pengguna Baru')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h3>👤 Form Pengguna Baru</h3>
            <a href="{{ route('admin.users') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="form-grid-2">
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-input" required value="{{ old('name') }}" placeholder="Nama lengkap sesuai akta/ijazah">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" required value="{{ old('email') }}" placeholder="email@manpandanaran.sch.id">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" required minlength="8" placeholder="Min. 8 karakter">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Peran</label>
                        <select name="role" class="form-input" required onchange="toggleClassField(this.value)">
                            <option value="siswa">🎓 Siswa</option>
                            <option value="guru">👨‍🏫 Guru</option>
                            <option value="bendahara">💰 Bendahara</option>
                            <option value="admin">🛡️ Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-input" required>
                            <option value="L">♂ Laki-laki</option>
                            <option value="P">♀ Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">NISN / NIP</label>
                        <input type="text" name="identity_number" class="form-input" value="{{ old('identity_number') }}" placeholder="Nomor identitas">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="+62 812 xxxx xxxx">
                    </div>
                    <div class="form-group" id="class-field">
                        <label class="form-label">Kelas (untuk Siswa)</label>
                        <select name="class_id" class="form-input">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-input" rows="2" placeholder="Alamat domisili lengkap"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem;">
                    <i class="bi bi-person-plus-fill"></i> Tambah Pengguna
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleClassField(role) {
    document.getElementById('class-field').style.opacity = role === 'siswa' ? '1' : '0.4';
}
</script>
@endsection
