@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')
@section('breadcrumb', 'Profil Saya')

@section('content')
<div class="cards-grid-3" style="align-items: flex-start;">

    <!-- Left Column: User Card -->
    <div class="card" style="grid-column: span 1;">
        <div class="card-body" style="text-align: center; padding: 2rem 1.5rem;">
            <div style="width: 84px; height: 84px; border-radius: 20px; background: linear-gradient(135deg, var(--emerald-700), var(--emerald-600)); display: inline-flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 2.2rem; box-shadow: 0 8px 24px rgba(4,120,87,0.3); margin-bottom: 1rem;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-800); margin-bottom: 0.35rem;">
                {{ $user->name }}
            </h3>

            <div style="margin-bottom: 1rem;">
                <span class="badge role-{{ $user->role }}" style="font-size: 0.75rem; padding: 4px 12px;">
                    <i class="bi bi-shield-check"></i> {{ ucfirst($user->role) }}
                </span>
            </div>

            <div style="background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: 12px; padding: 1rem; text-align: left; margin-top: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <i class="bi bi-envelope-fill" style="color: var(--emerald-600); font-size: 1.1rem;"></i>
                    <div>
                        <div style="font-size: 0.72rem; color: var(--slate-400); font-weight: 600;">EMAIL</div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--slate-700);">{{ $user->email }}</div>
                    </div>
                </div>

                @if($user->identity_number)
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <i class="bi bi-card-text" style="color: var(--emerald-600); font-size: 1.1rem;"></i>
                    <div>
                        <div style="font-size: 0.72rem; color: var(--slate-400); font-weight: 600;">NISN / NIP / NO. INDUK</div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--slate-700);">{{ $user->identity_number }}</div>
                    </div>
                </div>
                @endif

                @if($user->isSiswa() && $user->class)
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <i class="bi bi-building-fill" style="color: var(--emerald-600); font-size: 1.1rem;"></i>
                    <div>
                        <div style="font-size: 0.72rem; color: var(--slate-400); font-weight: 600;">KELAS</div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--slate-700);">{{ $user->class->name }}</div>
                    </div>
                </div>
                @endif

                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <i class="bi bi-calendar-check-fill" style="color: var(--emerald-600); font-size: 1.1rem;"></i>
                    <div>
                        <div style="font-size: 0.72rem; color: var(--slate-400); font-weight: 600;">TERDAFTAR SEJAK</div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--slate-700);">{{ $user->created_at->isoFormat('D MMMM YYYY') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Edit Profile Form -->
    <div class="card" style="grid-column: span 2;">
        <div class="card-header">
            <h3><i class="bi bi-person-gear"></i> Pengaturan Profil & Keamanan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--slate-700); margin-bottom: 1rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-person-lines-fill" style="color: var(--emerald-600);"></i> Informasi Pribadi
                </h4>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Alamat Email (Non-editable)</label>
                        <input type="email" class="form-input" value="{{ $user->email }}" disabled style="background: var(--slate-100); cursor: not-allowed;">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="phone">Nomor HP / WhatsApp</label>
                        <input type="text" name="phone" id="phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="gender">Jenis Kelamin</label>
                        <select name="gender" id="gender" class="form-input">
                            <option value="L" {{ old('gender', $user->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', $user->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--slate-700); margin-top: 1.5rem; margin-bottom: 1rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-shield-lock-fill" style="color: var(--emerald-600);"></i> Ubah Password (Opsional)
                </h4>

                <div class="form-group">
                    <label class="form-label" for="current_password">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" class="form-input" placeholder="Isi hanya jika ingin mengubah password">
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="new_password">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" class="form-input" placeholder="Minimal 8 karakter">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="new_password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-input" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div style="margin-top: 1.5rem; text-align: right;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.7rem 1.5rem;">
                        <i class="bi bi-save2-fill"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
