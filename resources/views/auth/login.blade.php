<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Madrasah Aliyah Sunan Pandanaran</title>
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

        .login-wrapper {
            display: grid; grid-template-columns: 1fr 1fr; gap: 0; max-width: 900px; width: 100%;
            background: white; border-radius: 24px; overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.35);
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        .login-left {
            background: linear-gradient(160deg, #064e3b, #065f46 50%, #0d9488);
            padding: 3rem 2.5rem; display: flex; flex-direction: column; justify-content: space-between;
        }
        .login-left-brand { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; }
        .login-logo {
            width: 48px; height: 48px; border-radius: 12px; flex-shrink: 0;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1.3rem; color: white;
            box-shadow: 0 4px 16px rgba(245,158,11,0.4);
        }
        .login-left-brand .name { color: white; font-weight: 800; font-size: 1.05rem; line-height: 1.2; }
        .login-left-brand .sub  { color: rgba(255,255,255,0.65); font-size: 0.72rem; }
        .login-left h2 { color: white; font-size: 1.8rem; font-weight: 800; margin-bottom: 0.75rem; line-height: 1.3; }
        .login-left p  { color: rgba(255,255,255,0.75); font-size: 0.875rem; line-height: 1.7; }

        .quick-logins { margin-top: 2rem; }
        .quick-logins h5 { color: rgba(255,255,255,0.7); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.75rem; }
        .quick-btn {
            display: flex; align-items: center; gap: 0.6rem; width: 100%;
            padding: 0.65rem 1rem; border-radius: 10px; margin-bottom: 0.5rem;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
            color: white; font-size: 0.8rem; font-weight: 500; cursor: pointer;
            transition: all 0.2s; font-family: inherit; text-align: left;
        }
        .quick-btn:hover { background: rgba(255,255,255,0.18); border-color: rgba(251,191,36,0.4); }
        .quick-btn .q-role {
            font-size: 0.65rem; background: rgba(251,191,36,0.2); color: #fbbf24;
            padding: 2px 8px; border-radius: 50px; font-weight: 700; margin-left: auto;
        }

        .login-right { padding: 3rem 2.5rem; display: flex; flex-direction: column; justify-content: center; }
        .login-right h3 { font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.4rem; }
        .login-right p  { font-size: 0.85rem; color: #64748b; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
        .form-input {
            width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0;
            border-radius: 10px; font-size: 0.875rem; font-family: inherit; color: #1e293b;
            background: #f8fafc; transition: all 0.2s;
        }
        .form-input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.1); background: white; }
        .form-input-group { position: relative; }
        .form-input-icon { position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1rem; }
        .form-input-group .form-input { padding-left: 2.5rem; }
        .toggle-password {
            position: absolute; right: 0.875rem; top: 50%; transform: translateY(-50%);
            color: #94a3b8; cursor: pointer; font-size: 1rem; background: none; border: none;
        }
        .error-text { color: #dc2626; font-size: 0.78rem; margin-top: 0.35rem; display: flex; align-items: center; gap: 0.3rem; }
        .checkbox-row { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; }
        .checkbox-row input[type="checkbox"] { accent-color: #047857; width: 16px; height: 16px; }
        .checkbox-row label { font-size: 0.82rem; color: #64748b; }
        .btn-login {
            width: 100%; padding: 0.875rem; background: linear-gradient(135deg, #047857, #059669);
            color: white; border: none; border-radius: 12px; font-weight: 800; font-size: 1rem;
            cursor: pointer; font-family: inherit; transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(5,150,105,0.35);
        }
        .btn-login:hover { background: linear-gradient(135deg, #065f46, #047857); transform: translateY(-2px); box-shadow: 0 12px 28px rgba(5,150,105,0.45); }

        .divider { display: flex; align-items: center; gap: 0.75rem; margin: 1.5rem 0; color: #cbd5e1; font-size: 0.75rem; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

        .back-home { display: inline-flex; align-items: center; gap: 0.4rem; color: #047857; font-size: 0.8rem; font-weight: 600; margin-top: 1.5rem; }
        .back-home:hover { text-decoration: underline; }

        @media (max-width: 640px) {
            .login-wrapper { grid-template-columns: 1fr; }
            .login-left { display: none; }
        }
    </style>
</head>
<body>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>

<div class="login-wrapper">
    <!-- Left panel -->
    <div class="login-left">
        <div>
            <div class="login-left-brand">
                <div class="login-logo">M</div>
                <div>
                    <div class="name">Madrasah Aliyah Sunan Pandanaran</div>
                    <div class="sub">Sistem Informasi Madrasah</div>
                </div>
            </div>
            <h2>Selamat Datang di Portal Digital Madrasah</h2>
            <p>Akses e-learning, jadwal pelajaran, materi pembelajaran, dan seluruh layanan madrasah dalam satu platform terpadu.</p>
        </div>

        {{-- Demo Akun Cepat disembunyikan --}}
    </div>

    <!-- Right panel -->
    <div class="login-right">
        <h3>Masuk ke Akun Anda</h3>
        <p>Masukkan email dan password terdaftar Anda</p>

        @if($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 10px; padding: 0.875rem 1rem; margin-bottom: 1.25rem; font-size: 0.83rem; color: #991b1b; display: flex; align-items: center; gap: 0.5rem;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" id="loginForm">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <div class="form-input-group">
                    <i class="bi bi-envelope-fill form-input-icon"></i>
                    <input type="email" name="email" id="email" class="form-input"
                        value="{{ old('email') }}" required placeholder="email@manpandanaran.sch.id">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="form-input-group">
                    <i class="bi bi-lock-fill form-input-icon"></i>
                    <input type="password" name="password" id="password" class="form-input" required placeholder="••••••••">
                    <button type="button" class="toggle-password" onclick="togglePwd()">
                        <i class="bi bi-eye" id="pwdToggleIcon"></i>
                    </button>
                </div>
            </div>
            <div class="checkbox-row" style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya</label>
                </div>
                <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: #047857; font-weight: 600; text-decoration: none;">Lupa Password?</a>
            </div>
            <button type="submit" class="btn-login" id="loginBtn">
                <i class="bi bi-box-arrow-in-right"></i> Masuk ke Sistem
            </button>
        </form>

        <a href="{{ route('landing') }}" class="back-home">
            <i class="bi bi-arrow-left"></i> Kembali ke Website Madrasah
        </a>
    </div>
</div>

<script>
function setLogin(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
    document.getElementById('email').focus();
}
function togglePwd() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('pwdToggleIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memverifikasi...';
    btn.disabled = true;
});
</script>
</body>
</html>
