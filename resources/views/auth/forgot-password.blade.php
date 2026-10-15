<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Madrasah Aliyah Sunan Pandanaran</title>
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

        .forgot-wrapper {
            display: grid; grid-template-columns: 1fr 1fr; gap: 0; max-width: 900px; width: 100%;
            background: white; border-radius: 24px; overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.35);
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        .forgot-left {
            background: linear-gradient(160deg, #064e3b, #065f46 50%, #0d9488);
            padding: 3rem 2.5rem; display: flex; flex-direction: column; justify-content: space-between;
        }
        .forgot-left-brand { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; }
        .forgot-logo {
            width: 48px; height: 48px; border-radius: 12px; flex-shrink: 0;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1.3rem; color: white;
            box-shadow: 0 4px 16px rgba(245,158,11,0.4);
        }
        .forgot-left-brand .name { color: white; font-weight: 800; font-size: 1.05rem; line-height: 1.2; }
        .forgot-left-brand .sub  { color: rgba(255,255,255,0.65); font-size: 0.72rem; }
        .forgot-left h2 { color: white; font-size: 1.8rem; font-weight: 800; margin-bottom: 0.75rem; line-height: 1.3; }
        .forgot-left p  { color: rgba(255,255,255,0.75); font-size: 0.875rem; line-height: 1.7; }

        .info-card {
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18);
            border-radius: 14px; padding: 1.25rem; color: white; margin-top: 2rem;
            backdrop-filter: blur(10px);
        }
        .info-card h4 { font-size: 0.9rem; font-weight: 700; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem; color: #fbbf24; }
        .info-card p { font-size: 0.8rem; opacity: 0.9; line-height: 1.6; }

        .forgot-right { padding: 3rem 2.5rem; display: flex; flex-direction: column; justify-content: center; }
        .forgot-right h3 { font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.4rem; }
        .forgot-right p  { font-size: 0.85rem; color: #64748b; margin-bottom: 1.5rem; }

        .instruction-box {
            background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px;
            padding: 1rem 1.1rem; margin-bottom: 1.5rem; font-size: 0.82rem; color: #1e40af;
            line-height: 1.6; display: flex; gap: 0.75rem; align-items: flex-start;
        }
        .instruction-box i { font-size: 1.25rem; color: #2563eb; flex-shrink: 0; margin-top: 2px; }

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

        .btn-submit {
            width: 100%; padding: 0.875rem; background: linear-gradient(135deg, #047857, #059669);
            color: white; border: none; border-radius: 12px; font-weight: 800; font-size: 1rem;
            cursor: pointer; font-family: inherit; transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(5,150,105,0.35);
        }
        .btn-submit:hover { background: linear-gradient(135deg, #065f46, #047857); transform: translateY(-2px); box-shadow: 0 12px 28px rgba(5,150,105,0.45); }

        .back-login { display: inline-flex; align-items: center; gap: 0.4rem; color: #047857; font-size: 0.82rem; font-weight: 700; margin-top: 1.5rem; text-decoration: none; }
        .back-login:hover { text-decoration: underline; }

        @media (max-width: 640px) {
            .forgot-wrapper { grid-template-columns: 1fr; }
            .forgot-left { display: none; }
        }
    </style>
</head>
<body>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>

<div class="forgot-wrapper">
    <!-- Left panel -->
    <div class="forgot-left">
        <div>
            <div class="forgot-left-brand">
                <div class="forgot-logo">M</div>
                <div>
                    <div class="name">Madrasah Aliyah Sunan Pandanaran</div>
                    <div class="sub">Sistem Informasi Madrasah</div>
                </div>
            </div>
            <h2>Pemulihan Kata Sandi Akun LMS</h2>
            <p>Layanan reset kata sandi resmi untuk siswa, guru, dan staf Madrasah Aliyah Sunan Pandanaran.</p>
        </div>

        <div class="info-card">
            <h4><i class="bi bi-shield-lock-fill"></i> Keamanan Akun Sekolah</h4>
            <p>Untuk keamanan data pengguna, instruksi reset kata sandi hanya akan dikirimkan ke alamat email resmi yang terdaftar pertama kali oleh sekolah.</p>
        </div>
    </div>

    <!-- Right panel -->
    <div class="forgot-right">
        <h3>Lupa Password?</h3>
        <p>Silakan ikuti instruksi di bawah ini untuk memulihkan akun Anda.</p>

        @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #bbf7d0; border-radius: 10px; padding: 0.875rem 1rem; margin-bottom: 1.25rem; font-size: 0.83rem; color: #166534; display: flex; align-items: flex-start; gap: 0.5rem; line-height: 1.5;">
            <i class="bi bi-check-circle-fill" style="margin-top: 2px;"></i>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        @if(session('info'))
        <div style="background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 10px; padding: 0.875rem 1rem; margin-bottom: 1.25rem; font-size: 0.83rem; color: #075985; display: flex; align-items: flex-start; gap: 0.5rem; line-height: 1.5;">
            <i class="bi bi-info-circle-fill" style="margin-top: 2px;"></i>
            <div>{{ session('info') }}</div>
        </div>
        @endif

        @if($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 10px; padding: 0.875rem 1rem; margin-bottom: 1.25rem; font-size: 0.83rem; color: #991b1b; display: flex; align-items: center; gap: 0.5rem;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <div class="instruction-box">
            <i class="bi bi-envelope-exclamation-fill"></i>
            <div>
                <strong>Petunjuk:</strong> Harap masukkan <strong>email yang pertama kali diberikan oleh pihak sekolah</strong> saat pembuatan akun pertama kali. Instruksi pemulihan password akan dikirim ke email tersebut.
            </div>
        </div>

        <form action="{{ route('password.email') }}" method="POST" id="forgotForm">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Alamat Email Sekolah / Terdaftar</label>
                <div class="form-input-group">
                    <i class="bi bi-envelope-fill form-input-icon"></i>
                    <input type="email" name="email" id="email" class="form-input"
                        value="{{ old('email') }}" required placeholder="email@manpandanaran.sch.id">
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="bi bi-send-fill"></i> Kirim Instruksi Reset
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-login">
            <i class="bi bi-arrow-left"></i> Kembali ke Halaman Login
        </a>
    </div>
</div>

<script>
document.getElementById('forgotForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengirim Instruksi...';
    btn.disabled = true;
});
</script>
</body>
</html>
