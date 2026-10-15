<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran PPDB | MA Sunan Pandanaran</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(150deg, #022c22 0%, #064e3b 45%, #065f46 75%, #0a7060 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 2rem; position: relative; overflow: hidden;
        }
        .orb {
            position: fixed; border-radius: 50%;
            background: radial-gradient(circle, rgba(251,191,36,0.2) 0%, transparent 70%);
            pointer-events: none;
        }
        .orb-1 { width: 500px; height: 500px; top: -150px; right: -150px; animation: float 8s ease-in-out infinite; }
        .orb-2 { width: 350px; height: 350px; bottom: -100px; left: -100px; animation: float 10s ease-in-out infinite 3s; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }

        .register-wrapper {
            display: grid; grid-template-columns: 1fr 1.3fr; gap: 0; max-width: 1000px; width: 100%;
            background: white; border-radius: 24px; overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.35);
            animation: slideUp 0.5s ease;
            position: relative; z-index: 10;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        .register-left {
            background: linear-gradient(160deg, #064e3b, #065f46 50%, #0d9488);
            padding: 3rem 2.5rem; display: flex; flex-direction: column; justify-content: space-between;
        }
        .register-left-brand { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; }
        .register-logo {
            width: 48px; height: 48px; border-radius: 12px; flex-shrink: 0;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1.3rem; color: white;
            box-shadow: 0 4px 16px rgba(245,158,11,0.4);
        }
        .register-left-brand .name { color: white; font-weight: 800; font-size: 1.05rem; line-height: 1.2; }
        .register-left-brand .sub  { color: rgba(255,255,255,0.65); font-size: 0.72rem; }
        .register-left h2 { color: white; font-size: 1.8rem; font-weight: 800; margin-bottom: 0.75rem; line-height: 1.3; }
        .register-left p  { color: rgba(255,255,255,0.75); font-size: 0.875rem; line-height: 1.7; }

        .features { margin-top: 2rem; }
        .feature-item {
            display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;
        }
        .feature-icon {
            background: rgba(255,255,255,0.1); border-radius: 10px; width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center; color: #fbbf24;
            font-size: 1.2rem; flex-shrink: 0;
        }
        .feature-text h4 { color: white; font-size: 0.95rem; font-weight: 700; margin-bottom: 0.25rem; }
        .feature-text p { color: rgba(255,255,255,0.7); font-size: 0.8rem; line-height: 1.4; margin: 0; }

        .register-right { padding: 3rem 2.5rem; display: flex; flex-direction: column; justify-content: center; max-height: 90vh; overflow-y: auto; }
        
        /* Custom scrollbar for right panel */
        .register-right::-webkit-scrollbar { width: 6px; }
        .register-right::-webkit-scrollbar-track { background: transparent; }
        .register-right::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .register-right::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .register-right h3 { font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.4rem; }
        .register-right p  { font-size: 0.85rem; color: #64748b; margin-bottom: 2rem; }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
        .form-input {
            width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0;
            border-radius: 10px; font-size: 0.875rem; font-family: inherit; color: #1e293b;
            background: #f8fafc; transition: all 0.2s;
        }
        .form-input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.1); background: white; }
        select.form-input { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem; padding-right: 2.5rem; }
        .form-input-group { position: relative; }
        .form-input-icon { position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1rem; }
        .form-input-group .form-input { padding-left: 2.5rem; }
        
        .btn-register {
            width: 100%; padding: 0.875rem; background: linear-gradient(135deg, #047857, #059669);
            color: white; border: none; border-radius: 12px; font-weight: 800; font-size: 1rem;
            cursor: pointer; font-family: inherit; transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(5,150,105,0.35); margin-top: 1rem;
        }
        .btn-register:hover { background: linear-gradient(135deg, #065f46, #047857); transform: translateY(-2px); box-shadow: 0 12px 28px rgba(5,150,105,0.45); }

        .back-home { display: inline-flex; align-items: center; gap: 0.4rem; color: #047857; font-size: 0.8rem; font-weight: 600; margin-top: 1.5rem; justify-content: center; }
        .back-home:hover { text-decoration: underline; }
        
        .login-link { text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: #64748b; }
        .login-link a { color: #047857; font-weight: 700; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }

        @media (max-width: 768px) {
            .register-wrapper { grid-template-columns: 1fr; }
            .register-left { display: none; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>

<div class="register-wrapper">
    <!-- Left panel -->
    <div class="register-left">
        <div>
            <div class="register-left-brand">
                <div class="register-logo">M</div>
                <div>
                    <div class="name">MA Sunan Pandanaran</div>
                    <div class="sub">Penerimaan Peserta Didik Baru</div>
                </div>
            </div>
            <h2>Bergabunglah Bersama Generasi Unggul</h2>
            <p>Daftarkan diri Anda sekarang untuk menjadi bagian dari madrasah berprestasi berbasis nilai Qur'ani & sains modern.</p>
        </div>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon"><i class="bi bi-book"></i></div>
                <div class="feature-text">
                    <h4>Program Tahfidz Qur'an</h4>
                    <p>Hafalan bersanad didampingi hafizh lulusan Al-Azhar.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="bi bi-robot"></i></div>
                <div class="feature-text">
                    <h4>Sains & Robotika</h4>
                    <p>Fasilitas laboratorium AI, coding, dan IoT terpadu.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="bi bi-translate"></i></div>
                <div class="feature-text">
                    <h4>Bilingual Internasional</h4>
                    <p>Lingkungan berbahasa Arab & Inggris setiap hari.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right panel -->
    <div class="register-right">
        <h3>Formulir Pendaftaran PPDB</h3>
        <p>Lengkapi data diri di bawah ini untuk membuat akun PPDB</p>

        @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #bbf7d0; border-radius: 10px; padding: 0.875rem 1rem; margin-bottom: 1.25rem; font-size: 0.83rem; color: #166534; display: flex; align-items: center; gap: 0.5rem;">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 10px; padding: 0.875rem 1rem; margin-bottom: 1.25rem; font-size: 0.83rem; color: #991b1b; display: flex; align-items: center; gap: 0.5rem;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            Terdapat kesalahan pada isian form Anda.
        </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" id="registerForm">
            @csrf
            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap</label>
                <div class="form-input-group">
                    <i class="bi bi-person-fill form-input-icon"></i>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required placeholder="Sesuai Akte Kelahiran / Ijazah">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="nisn">NISN</label>
                    <div class="form-input-group">
                        <i class="bi bi-credit-card-2-front-fill form-input-icon"></i>
                        <input type="text" name="nisn" id="nisn" class="form-input" value="{{ old('nisn') }}" required placeholder="10 Digit NISN">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">No. WhatsApp / HP Aktif</label>
                    <div class="form-input-group">
                        <i class="bi bi-telephone-fill form-input-icon"></i>
                        <input type="text" name="phone" id="phone" class="form-input" value="{{ old('phone') }}" required placeholder="0812xxxxxxxx">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="school">Asal Sekolah (SMP/MTs)</label>
                <div class="form-input-group">
                    <i class="bi bi-building-fill form-input-icon"></i>
                    <input type="text" name="school" id="school" class="form-input" value="{{ old('school') }}" required placeholder="Nama Sekolah Asal">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="program">Program Peminatan</label>
                <select name="program" id="program" class="form-input" required>
                    <option value="" disabled selected>-- Pilih Program Peminatan --</option>
                    <option value="tahfidz">Program Tahfidz Al-Qur'an (Asrama)</option>
                    <option value="sains">Sains, Robotika & Informatika</option>
                    <option value="bilingual">Bilingual Arab & Inggris</option>
                    <option value="reguler">Kelas Reguler / Umum</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="form-input-group">
                        <i class="bi bi-envelope-fill form-input-icon"></i>
                        <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required placeholder="email@contoh.com">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="form-input-group">
                        <i class="bi bi-lock-fill form-input-icon"></i>
                        <input type="password" name="password" id="password" class="form-input" required placeholder="Minimal 8 karakter">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-register" id="registerBtn">
                <i class="bi bi-pencil-square"></i> Daftar Sekarang
            </button>
        </form>

        <a href="{{ route('landing') }}" class="back-home">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function() {
    const btn = document.getElementById('registerBtn');
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
    btn.disabled = true;
});
</script>
</body>
</html>
