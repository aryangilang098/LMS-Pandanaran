<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MA Sunan Pandanaran, Institusi Pendidikan Islam Modern Berprestasi Internasional di Yogyakarta">
    <title>@yield('title', 'Beranda') | MA Sunan Pandanaran</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --emerald-50: #ecfdf5;
            --emerald-100: #d1fae5;
            --emerald-500: #10b981;
            --emerald-600: #059669;
            --emerald-700: #047857;
            --emerald-800: #065f46;
            --emerald-900: #064e3b;
            --gold-400: #fbbf24;
            --gold-500: #f59e0b;
            --gold-600: #d97706;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --white: #ffffff;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; color: var(--slate-800); background: #f8fafc; overflow-x: hidden; }
        a { text-decoration: none; color: inherit; }

        /* ── NAVBAR ──────────────────────────────────────────────────────────── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between;
            background: rgba(6, 78, 59, 0.08);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }
        .navbar.scrolled {
            background: rgba(6, 78, 59, 0.96);
            box-shadow: 0 4px 30px rgba(0,0,0,0.3);
        }
        .navbar-brand { display: flex; align-items: center; gap: 0.75rem; }
        .navbar-logo {
            width: 44px; height: 44px; border-radius: 10px;
            background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1.1rem; color: white;
            box-shadow: 0 4px 12px rgba(245,158,11,0.4);
        }
        .navbar-name { color: white; font-weight: 700; font-size: 1.05rem; line-height: 1.2; }
        .navbar-name small { display: block; font-size: 0.72rem; font-weight: 400; opacity: 0.8; }
        .navbar-nav { display: flex; align-items: center; gap: 0.25rem; list-style: none; }
        .navbar-nav a {
            color: rgba(255,255,255,0.85); font-size: 0.875rem; font-weight: 500;
            padding: 0.5rem 0.875rem; border-radius: 8px; transition: all 0.2s;
        }
        .navbar-nav a:hover { color: white; background: rgba(255,255,255,0.15); }
        .btn-nav-login {
            background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
            color: white !important; padding: 0.5rem 1.25rem !important;
            border-radius: 8px !important; font-weight: 600 !important;
            box-shadow: 0 4px 12px rgba(245,158,11,0.3);
        }
        .btn-nav-login:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(245,158,11,0.4) !important; background: rgba(255,255,255,0.2) !important; }
        .navbar-toggle { display: none; background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; }

        /* ── HERO ────────────────────────────────────────────────────────────── */
        .hero {
            min-height: 100vh; position: relative; overflow: hidden;
            background: linear-gradient(150deg, #022c22 0%, #064e3b 45%, #065f46 75%, #0a7060 100%);
            display: flex; align-items: center;
        }
        .hero-orbs {
            position: absolute; inset: 0; pointer-events: none;
        }
        .hero-orb {
            position: absolute; border-radius: 50%;
            background: radial-gradient(circle, rgba(251,191,36,0.2) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite;
        }
        .hero-orb-1 { width: 500px; height: 500px; top: -100px; right: -100px; animation-delay: 0s; }
        .hero-orb-2 { width: 300px; height: 300px; bottom: 50px; left: -50px; animation-delay: 3s; }
        @keyframes float { 0%, 100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-20px) scale(1.05); } }

        .hero-content {
            position: relative; max-width: 1200px; margin: 0 auto;
            padding: 8rem 2rem 4rem; display: grid;
            grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(251,191,36,0.15); border: 1px solid rgba(251,191,36,0.3);
            color: var(--gold-400); padding: 0.4rem 1rem; border-radius: 50px;
            font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem;
            animation: slideInDown 0.6s ease;
        }
        .hero-title {
            font-size: clamp(2.2rem, 4vw, 3.2rem); font-weight: 800; color: white;
            line-height: 1.15; margin-bottom: 1.25rem;
            animation: slideInLeft 0.7s ease;
        }
        .hero-title .accent { color: var(--gold-400); }
        .hero-subtitle {
            color: rgba(255,255,255,0.8); font-size: 1.05rem; line-height: 1.7;
            margin-bottom: 2rem; animation: slideInLeft 0.8s ease;
        }
        .hero-stats {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem;
        }
        .hero-stat {
            text-align: center; background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12); border-radius: 12px;
            padding: 0.875rem; backdrop-filter: blur(10px);
        }
        .hero-stat-num { font-size: 1.6rem; font-weight: 800; color: var(--gold-400); }
        .hero-stat-label { font-size: 0.7rem; color: rgba(255,255,255,0.7); margin-top: 2px; }
        .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; animation: slideInLeft 0.9s ease; }
        .btn-primary-hero {
            background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
            color: white; padding: 0.875rem 1.75rem; border-radius: 12px;
            font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer;
            box-shadow: 0 8px 24px rgba(245,158,11,0.4); transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-primary-hero:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(245,158,11,0.5); color: white; }
        .btn-outline-hero {
            background: rgba(255,255,255,0.08); border: 1.5px solid rgba(255,255,255,0.3);
            color: white; padding: 0.875rem 1.75rem; border-radius: 12px;
            font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-outline-hero:hover { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.5); transform: translateY(-2px); color: white; }

        .hero-visual {
            position: relative; animation: slideInRight 0.7s ease;
        }
        .hero-card-group {
            display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
        }
        .hero-card {
            background: rgba(255,255,255,0.08); backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.15); border-radius: 16px; padding: 1.5rem;
            transition: transform 0.3s;
        }
        .hero-card:hover { transform: translateY(-5px); }
        .hero-card.featured { grid-column: span 2; background: rgba(251,191,36,0.1); border-color: rgba(251,191,36,0.25); }
        .hero-card-icon {
            width: 44px; height: 44px; border-radius: 10px; margin-bottom: 0.75rem;
            display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
        }
        .ic-emerald { background: rgba(16,185,129,0.2); color: #34d399; }
        .ic-gold    { background: rgba(245,158,11,0.2); color: var(--gold-400); }
        .ic-blue    { background: rgba(59,130,246,0.2); color: #60a5fa; }
        .ic-purple  { background: rgba(168,85,247,0.2); color: #c084fc; }
        .hero-card h4 { color: white; font-size: 0.9rem; font-weight: 700; margin-bottom: 0.25rem; }
        .hero-card p { color: rgba(255,255,255,0.65); font-size: 0.78rem; line-height: 1.5; }

        @keyframes slideInLeft  { from { opacity: 0; transform: translateX(-40px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes slideInRight { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes slideInDown  { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

        /* ── SECTIONS ────────────────────────────────────────────────────────── */
        .section { padding: 5rem 2rem; }
        .section-alt { background: white; }
        .section-dark { background: linear-gradient(135deg, #064e3b, #065f46); color: white; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section-header { text-align: center; margin-bottom: 3.5rem; }
        .section-badge {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: var(--emerald-50); border: 1px solid var(--emerald-100);
            color: var(--emerald-700); padding: 0.35rem 0.875rem; border-radius: 50px;
            font-size: 0.78rem; font-weight: 600; margin-bottom: 1rem;
        }
        .section-badge.gold-badge { background: #fffbeb; border-color: #fde68a; color: var(--gold-600); }
        .section-title {
            font-size: clamp(1.8rem, 3vw, 2.4rem); font-weight: 800; color: var(--slate-800);
            line-height: 1.2; margin-bottom: 0.75rem;
        }
        .section-title .accent { color: var(--emerald-700); }
        .section-subtitle { color: #64748b; font-size: 1rem; max-width: 600px; margin: 0 auto; line-height: 1.7; }
        .white-text .section-title { color: white; }
        .white-text .section-subtitle { color: rgba(255,255,255,0.75); }

        /* ── PROGRAM CARDS ───────────────────────────────────────────────────── */
        .programs-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .program-card {
            border-radius: 20px; overflow: hidden; position: relative;
            background: white; border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06); transition: all 0.4s ease;
        }
        .program-card:hover { transform: translateY(-8px); box-shadow: 0 20px 50px rgba(0,0,0,0.12); }
        .program-card-header {
            padding: 2rem; background: linear-gradient(135deg, var(--emerald-700), var(--emerald-600));
        }
        .program-card-header.gold { background: linear-gradient(135deg, var(--gold-600), #b45309); }
        .program-card-header.teal { background: linear-gradient(135deg, #0d9488, #0891b2); }
        .program-icon { font-size: 2.5rem; margin-bottom: 1rem; }
        .program-card-header h3 { color: white; font-size: 1.2rem; font-weight: 700; }
        .program-card-header p { color: rgba(255,255,255,0.8); font-size: 0.85rem; margin-top: 0.35rem; }
        .program-card-body { padding: 1.5rem; }
        .program-features { list-style: none; }
        .program-features li {
            padding: 0.5rem 0; font-size: 0.875rem; color: #475569;
            border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.5rem;
        }
        .program-features li:last-child { border-bottom: none; }
        .program-features li::before { content: '✦'; color: var(--emerald-600); font-size: 0.6rem; }

        /* ── NEWS CARDS ──────────────────────────────────────────────────────── */
        .news-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .news-card {
            background: white; border-radius: 16px; overflow: hidden;
            border: 1px solid #e2e8f0; box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            transition: all 0.3s; display: flex; flex-direction: column;
        }
        .news-card:hover { transform: translateY(-5px); box-shadow: 0 12px 35px rgba(0,0,0,0.1); }
        .news-thumbnail {
            height: 180px; background: linear-gradient(135deg, var(--emerald-700), var(--emerald-600));
            display: flex; align-items: center; justify-content: center; font-size: 3rem;
            position: relative; overflow: hidden;
        }
        .news-cat-badge {
            position: absolute; top: 12px; left: 12px;
            background: white; color: var(--emerald-700); padding: 3px 10px;
            border-radius: 50px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        }
        .news-cat-badge.prestasi { background: var(--gold-500); color: white; }
        .news-cat-badge.pengumuman { background: #3b82f6; color: white; }
        .news-cat-badge.agenda { background: #8b5cf6; color: white; }
        .news-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
        .news-date { font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.5rem; }
        .news-title { font-size: 0.95rem; font-weight: 700; color: var(--slate-800); line-height: 1.4; margin-bottom: 0.5rem; flex: 1; }
        .news-excerpt { font-size: 0.8rem; color: #64748b; line-height: 1.6; }
        .news-card .read-more {
            display: inline-flex; align-items: center; gap: 0.35rem;
            color: var(--emerald-600); font-size: 0.8rem; font-weight: 600;
            margin-top: 1rem; transition: gap 0.2s;
        }
        .news-card .read-more:hover { gap: 0.6rem; }

        /* ── FACILITIES ──────────────────────────────────────────────────────── */
        .facilities-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
        .facility-card {
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px; padding: 1.75rem 1.25rem; text-align: center;
            transition: all 0.3s; cursor: default;
        }
        .facility-card:hover {
            background: rgba(255,255,255,0.14); transform: translateY(-4px);
            border-color: rgba(251,191,36,0.4);
        }
        .facility-icon { font-size: 2.4rem; margin-bottom: 1rem; }
        .facility-card h4 { color: white; font-weight: 700; margin-bottom: 0.35rem; font-size: 0.95rem; }
        .facility-card p { color: rgba(255,255,255,0.65); font-size: 0.78rem; line-height: 1.5; }

        /* ── PPDB CTA ────────────────────────────────────────────────────────── */
        .ppdb-section {
            padding: 5rem 2rem;
            background: linear-gradient(135deg, var(--gold-600) 0%, var(--gold-500) 50%, #fbbf24 100%);
            position: relative; overflow: hidden;
        }
        .ppdb-content { max-width: 1000px; margin: 0 auto; text-align: center; }
        .ppdb-content h2 { font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 1rem; }
        .ppdb-content p { color: rgba(255,255,255,0.9); font-size: 1.05rem; margin-bottom: 2rem; }
        .ppdb-info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2.5rem; }
        .ppdb-info-card {
            background: rgba(255,255,255,0.2); border-radius: 14px; padding: 1.5rem;
            text-align: left; border: 1px solid rgba(255,255,255,0.3);
        }
        .ppdb-info-card .icon { font-size: 1.8rem; margin-bottom: 0.75rem; }
        .ppdb-info-card h4 { color: white; font-weight: 700; font-size: 1rem; margin-bottom: 0.25rem; }
        .ppdb-info-card p { color: rgba(255,255,255,0.85); font-size: 0.82rem; }
        .btn-ppdb-register {
            background: white; color: var(--gold-600); padding: 1rem 2.5rem;
            border-radius: 14px; font-weight: 800; font-size: 1rem; border: none; cursor: pointer;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2); transition: all 0.3s; display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-ppdb-register:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(0,0,0,0.3); color: var(--gold-600); }

        /* ── CONTACT ─────────────────────────────────────────────────────────── */
        .contact-grid { display: grid; grid-template-columns: 1fr 1.4fr; gap: 3rem; align-items: start; }
        .contact-info h3 { font-size: 1.4rem; font-weight: 800; color: var(--slate-800); margin-bottom: 1.5rem; }
        .contact-item {
            display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.25rem;
        }
        .contact-item-icon {
            width: 44px; height: 44px; border-radius: 10px; flex-shrink: 0;
            background: var(--emerald-50); color: var(--emerald-700);
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        }
        .contact-item h4 { font-size: 0.875rem; font-weight: 700; color: var(--slate-800); }
        .contact-item p { font-size: 0.82rem; color: #64748b; line-height: 1.5; margin-top: 2px; }
        .contact-form { background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; font-size: 0.83rem; font-weight: 600; color: var(--slate-700); margin-bottom: 0.4rem; }
        .form-control {
            width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 0.875rem; font-family: inherit; background: #f8fafc; transition: all 0.2s;
            color: var(--slate-800);
        }
        .form-control:focus { outline: none; border-color: var(--emerald-500); background: white; box-shadow: 0 0 0 3px rgba(16,185,129,0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        textarea.form-control { resize: vertical; min-height: 110px; }
        .btn-submit {
            width: 100%; padding: 0.875rem; background: linear-gradient(135deg, var(--emerald-700), var(--emerald-600));
            color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.95rem;
            cursor: pointer; transition: all 0.3s; font-family: inherit;
        }
        .btn-submit:hover { background: linear-gradient(135deg, var(--emerald-800), var(--emerald-700)); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(5,150,105,0.35); }

        /* ── FOOTER ──────────────────────────────────────────────────────────── */
        .footer {
            background: var(--slate-900); color: rgba(255,255,255,0.7); padding: 4rem 2rem 2rem;
        }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 2.5rem; max-width: 1200px; margin: 0 auto 3rem; }
        .footer-brand .navbar-logo { width: 50px; height: 50px; font-size: 1.2rem; margin-bottom: 1rem; }
        .footer-brand p { font-size: 0.85rem; line-height: 1.7; max-width: 280px; margin-top: 0.75rem; }
        .footer-col h5 { color: white; font-weight: 700; font-size: 0.95rem; margin-bottom: 1.25rem; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 0.6rem; }
        .footer-links a { font-size: 0.83rem; transition: color 0.2s; }
        .footer-links a:hover { color: var(--gold-400); }
        .footer-bottom { max-width: 1200px; margin: 0 auto; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .footer-bottom p { font-size: 0.8rem; }

        /* ── ALERTS / FLASH ──────────────────────────────────────────────────── */
        .flash-message {
            padding: 0.9rem 1.5rem; border-radius: 12px; margin-bottom: 1rem;
            font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem;
            animation: slideInDown 0.4s ease;
        }
        .flash-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .flash-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .flash-info    { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }

        /* ── PROFIL / IDENTITAS ──────────────────────────────────────────────── */
        .identity-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 3rem;
        }
        .identity-card {
            background: white; border: 1px solid #e2e8f0; border-radius: 14px;
            padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04); transition: all 0.3s;
        }
        .identity-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); border-color: var(--emerald-100); }
        .identity-icon {
            width: 44px; height: 44px; border-radius: 10px; flex-shrink: 0;
            background: var(--emerald-50); color: var(--emerald-700);
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        }
        .identity-label { font-size: 0.72rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .identity-value { font-size: 0.92rem; font-weight: 700; color: var(--slate-800); margin-top: 2px; }

        /* History Block */
        .history-block { display: grid; grid-template-columns: 1fr 300px; gap: 3rem; align-items: start; }
        .history-title { font-size: 1.4rem; font-weight: 800; color: var(--slate-800); margin-bottom: 1rem; line-height: 1.3; }
        .history-text p { font-size: 0.9rem; color: #475569; line-height: 1.75; }
        .history-milestones { margin-top: 1.75rem; display: flex; flex-direction: column; gap: 0.85rem; }
        .milestone { display: flex; gap: 1rem; align-items: flex-start; }
        .milestone-year {
            background: linear-gradient(135deg, var(--emerald-700), var(--emerald-600));
            color: white; font-weight: 800; font-size: 0.78rem;
            padding: 0.3rem 0.65rem; border-radius: 6px; white-space: nowrap; flex-shrink: 0;
        }
        .milestone-event { font-size: 0.85rem; color: #475569; padding-top: 0.25rem; }
        .history-stats-panel {
            background: linear-gradient(135deg, var(--emerald-700), var(--emerald-800));
            border-radius: 20px; padding: 2rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;
            position: sticky; top: 100px;
        }
        .stats-panel-item { text-align: center; }
        .stats-panel-num { font-size: 1.8rem; font-weight: 800; color: var(--gold-400); }
        .stats-panel-label { font-size: 0.72rem; color: rgba(255,255,255,0.75); margin-top: 4px; }

        /* ── VISI & MISI ─────────────────────────────────────────────────────── */
        .visi-misi-section {
            background: linear-gradient(150deg, #022c22 0%, #064e3b 50%, #065f46 100%);
        }
        .visi-misi-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 2rem; margin-bottom: 3rem; }
        .visi-card, .misi-card {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
            border-radius: 20px; padding: 2rem; backdrop-filter: blur(10px);
            transition: all 0.3s;
        }
        .visi-card:hover, .misi-card:hover { background: rgba(255,255,255,0.1); transform: translateY(-4px); }
        .visi-card-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
        .visi-icon {
            width: 50px; height: 50px; border-radius: 12px; flex-shrink: 0;
            background: rgba(16,185,129,0.2); color: #34d399;
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
        }
        .visi-card-header h3 { color: white; font-size: 1.15rem; font-weight: 700; }
        .visi-statement {
            border-left: 3px solid #34d399; padding-left: 1.25rem; margin: 0;
            font-size: 1rem; color: rgba(255,255,255,0.9); line-height: 1.8; font-style: italic;
        }
        .misi-list { list-style: none; display: flex; flex-direction: column; gap: 0.75rem; }
        .misi-list li {
            display: flex; align-items: flex-start; gap: 0.75rem;
            font-size: 0.875rem; color: rgba(255,255,255,0.85); line-height: 1.6;
        }
        .misi-list li i { color: #34d399; font-size: 1rem; flex-shrink: 0; margin-top: 2px; }
        .nilai-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; }
        .nilai-card {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px; padding: 1.75rem 1.25rem; text-align: center; transition: all 0.3s;
        }
        .nilai-card:hover { background: rgba(251,191,36,0.1); border-color: rgba(251,191,36,0.3); transform: translateY(-4px); }
        .nilai-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
        .nilai-card h4 { color: white; font-weight: 700; margin-bottom: 0.5rem; }
        .nilai-card p { color: rgba(255,255,255,0.65); font-size: 0.8rem; line-height: 1.5; }

        /* ── KEPALA MADRASAH ─────────────────────────────────────────────────── */
        .kepala-highlight {
            display: grid; grid-template-columns: 260px 1fr; gap: 3rem;
            background: linear-gradient(135deg, var(--emerald-50), white);
            border: 1px solid var(--emerald-100); border-radius: 24px;
            padding: 2.5rem; margin-bottom: 3rem; align-items: center;
        }
        .kepala-photo-wrapper { text-align: center; }
        .kepala-photo-placeholder {
            width: 180px; height: 180px; border-radius: 50%; margin: 0 auto 1rem;
            background: linear-gradient(135deg, var(--emerald-700), var(--emerald-600));
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 12px 40px rgba(4,120,87,0.35);
        }
        .kepala-badge-tag {
            display: inline-block; background: var(--emerald-700); color: white;
            padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.78rem; font-weight: 700;
        }
        .kepala-name { font-size: 1.7rem; font-weight: 800; color: var(--slate-800); margin-bottom: 0.5rem; }
        .kepala-tagline { font-size: 0.95rem; color: var(--emerald-700); font-style: italic; margin-bottom: 1.5rem; }
        .kepala-details { display: flex; flex-direction: column; gap: 0.85rem; }
        .kepala-detail-item { display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.875rem; color: #475569; }
        .kepala-detail-item i { color: var(--emerald-600); font-size: 1rem; flex-shrink: 0; margin-top: 2px; }
        .wakil-subtitle { font-size: 1.2rem; font-weight: 800; color: var(--slate-800); margin-bottom: 1.5rem; }
        .wakil-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; }
        .wakil-card {
            background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.5rem;
            text-align: center; transition: all 0.3s; box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        }
        .wakil-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); border-color: var(--emerald-100); }
        .wakil-photo {
            width: 72px; height: 72px; border-radius: 50%; margin: 0 auto 1rem;
            background: linear-gradient(135deg, var(--emerald-600), var(--emerald-700));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: rgba(255,255,255,0.7);
        }
        .wakil-info h4 { font-size: 0.9rem; font-weight: 700; color: var(--slate-800); margin-bottom: 0.4rem; }
        .wakil-role {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: var(--emerald-50); color: var(--emerald-700);
            padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.72rem; font-weight: 600;
            margin-bottom: 0.75rem;
        }
        .wakil-info p { font-size: 0.78rem; color: #64748b; line-height: 1.5; }

        /* ── SCROLL ANIMATIONS ───────────────────────────────────────────────── */
        .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .reveal.revealed { opacity: 1; transform: translateY(0); }

        /* ── RESPONSIVE ──────────────────────────────────────────────────────── */
        @media (max-width: 1024px) {
            .programs-grid, .news-grid { grid-template-columns: repeat(2, 1fr); }
            .facilities-grid { grid-template-columns: repeat(2, 1fr); }
            .ppdb-info-grid { grid-template-columns: 1fr 1fr; }
            .hero-content { grid-template-columns: 1fr; text-align: center; }
            .hero-stats { justify-content: center; }
            .hero-actions { justify-content: center; }
            .hero-visual { display: none; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .identity-grid { grid-template-columns: repeat(2, 1fr); }
            .history-block { grid-template-columns: 1fr; }
            .history-stats-panel { position: static; }
            .visi-misi-grid { grid-template-columns: 1fr; }
            .nilai-grid { grid-template-columns: repeat(2, 1fr); }
            .kepala-highlight { grid-template-columns: 1fr; text-align: center; }
            .wakil-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .navbar-nav { display: none; }
            .navbar-toggle { display: block; }
            .programs-grid, .news-grid, .facilities-grid { grid-template-columns: 1fr; }
            .contact-grid { grid-template-columns: 1fr; }
            .ppdb-info-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; text-align: center; }
            .identity-grid { grid-template-columns: 1fr; }
            .nilai-grid { grid-template-columns: 1fr 1fr; }
            .wakil-grid { grid-template-columns: 1fr; }
        }
    </style>
    @yield('head')
</head>
<body>

<!-- ─── NAVBAR ─────────────────────────────────────────────────────────────── -->
<nav class="navbar" id="mainNav">
    <div class="navbar-brand">
        <div class="navbar-logo">M</div>
        <div class="navbar-name">
            MA Sunan Pandanaran
            <small>Madrasah Aliyah</small>
        </div>
    </div>
    <ul class="navbar-nav" id="navLinks">
        <li><a href="{{ route('landing') }}#beranda">Beranda</a></li>
        <li><a href="{{ route('landing') }}#profil">Profil</a></li>
        <li><a href="{{ route('landing') }}#program">Program</a></li>
        <li><a href="{{ route('landing') }}#berita">Berita</a></li>
        <li><a href="{{ route('landing') }}#fasilitas">Fasilitas</a></li>
        <li><a href="{{ route('landing') }}#ppdb">PPDB</a></li>
        <li><a href="{{ route('landing') }}#kontak">Kontak</a></li>
        @auth
            <li><a href="{{ route('dashboard') }}" class="btn-nav-login"><i class="bi bi-grid-3x3-gap-fill me-1"></i>Dashboard</a></li>
        @else
            <li><a href="{{ route('login') }}" class="btn-nav-login"><i class="bi bi-box-arrow-in-right"></i> Masuk</a></li>
        @endauth
    </ul>
    <button class="navbar-toggle" onclick="document.getElementById('navLinks').style.display = document.getElementById('navLinks').style.display === 'flex' ? 'none' : 'flex'">
        <i class="bi bi-list"></i>
    </button>
</nav>

@yield('content')

<!-- ─── FOOTER ─────────────────────────────────────────────────────────────── -->
<footer class="footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <div class="navbar-logo" style="background: linear-gradient(135deg, #f59e0b, #d97706);">M</div>
            <p style="color: white; font-size: 1rem; font-weight: 700; margin-top: 0.5rem;">MA Sunan Pandanaran</p>
            <p>MA Sunan Pandanaran — Mencetak generasi Muslim intelektual berkarakter, berprestasi di tatanan global berlandaskan nilai-nilai Qur'ani.</p>
            <div style="display: flex; gap: 0.75rem; margin-top: 1.25rem;">
                <a href="#" style="width: 36px; height: 36px; background: rgba(255,255,255,0.08); border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(251,191,36,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'"><i class="bi bi-instagram" style="color: #e1306c;"></i></a>
                <a href="#" style="width: 36px; height: 36px; background: rgba(255,255,255,0.08); border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(251,191,36,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'"><i class="bi bi-youtube" style="color: #ff0000;"></i></a>
                <a href="#" style="width: 36px; height: 36px; background: rgba(255,255,255,0.08); border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(251,191,36,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'"><i class="bi bi-facebook" style="color: #1877f2;"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h5>Tautan Cepat</h5>
            <ul class="footer-links">
                <li><a href="{{ route('landing') }}#beranda">Beranda</a></li>
                <li><a href="{{ route('landing') }}#profil">Profil Madrasah</a></li>
                <li><a href="{{ route('landing') }}#visi-misi">Visi & Misi</a></li>
                <li><a href="{{ route('landing') }}#program">Program Unggulan</a></li>
                <li><a href="{{ route('landing') }}#berita">Berita & Agenda</a></li>
                <li><a href="{{ route('landing') }}#fasilitas">Fasilitas</a></li>
                <li><a href="{{ route('landing') }}#ppdb">PPDB 2026/2027</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h5>Sistem Digital</h5>
            <ul class="footer-links">
                <li><a href="{{ route('login') }}">Login Siswa/Guru</a></li>
                <li><a href="{{ route('login') }}">E-Learning (LMS)</a></li>
                <li><a href="{{ route('login') }}">Portal Pembayaran SPP</a></li>
                <li><a href="{{ route('login') }}">Laporan Nilai & Raport</a></li>
                <li><a href="{{ route('login') }}">Jadwal Pelajaran</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h5>Info Madrasah</h5>
            <ul class="footer-links">
                <li><a href="{{ route('landing') }}#profil">Profil Madrasah</a></li>
                <li><a href="{{ route('landing') }}#visi-misi">Visi & Misi</a></li>
                <li><a href="{{ route('landing') }}#kepala-madrasah">Pimpinan Madrasah</a></li>
                <li><a href="{{ route('landing') }}#fasilitas">Sarana & Prasarana</a></li>
                <li><a href="#">Download Panduan PPDB</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© {{ date('Y') }} MA Sunan Pandanaran — Seluruh hak cipta dilindungi undang-undang.</p>
        <p>Jl. Kaliurang KM 14.5, Ngemplak, Sleman, D.I. Yogyakarta 55584</p>
    </div>
</footer>

<script>
    // Navbar scroll effect
    const navbar = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 50);
    });

    // Scroll reveal
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('revealed'), i * 80);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08 });
    reveals.forEach(el => observer.observe(el));
</script>
@yield('scripts')
</body>
</html>
