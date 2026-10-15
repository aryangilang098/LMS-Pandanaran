@extends('layouts.landing')

@section('title', 'Beranda - MA Sunan Pandanaran')

@section('content')
<!-- ─── HERO ──────────────────────────────────────────────────────────────── -->
<section class="hero" id="beranda">
    <div class="hero-orbs">
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
    </div>
    <div class="hero-content">
        <div>
            <div class="hero-badge">
                <i class="bi bi-star-fill"></i> Akreditasi A — BAN-S/M Unggul
            </div>
            <h1 class="hero-title">
                Madrasah <span class="accent">Berprestasi</span> Berbasis<br>
                Nilai <span class="accent">Qur'ani</span> & Sains Modern
            </h1>
            <p class="hero-subtitle">
                MA Sunan Pandanaran mencetak generasi Muslim intelektual berwawasan global,
                hafizh Qur'an, kompeten di bidang sains-teknologi, dan berjiwa pemimpin.
            </p>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-num">1.200+</div>
                    <div class="hero-stat-label">Santri Aktif</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num">95+</div>
                    <div class="hero-stat-label">Tenaga Pendidik</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num">48+</div>
                    <div class="hero-stat-label">Prestasi Nasional</div>
                </div>
            </div>
            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn-primary-hero">
                    <i class="bi bi-pencil-square"></i> Daftar PPDB 2026/2027
                </a>
                <a href="#program" class="btn-outline-hero">
                    <i class="bi bi-play-circle-fill"></i> Lihat Program Unggulan
                </a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-card-group">
                <div class="hero-card featured">
                    <div class="hero-card-icon ic-gold"><i class="bi bi-book-half"></i></div>
                    <h4>Program Tahfidz Qur'an 30 Juz</h4>
                    <p>Target hafalan terstruktur dengan metode mutqin, didampingi hafizh berpengalaman lulusan Timur Tengah.</p>
                </div>
                <div class="hero-card">
                    <div class="hero-card-icon ic-blue"><i class="bi bi-robot"></i></div>
                    <h4>Lab Robotika & AI</h4>
                    <p>Fasilitas sains mutakhir: robotika IoT, coding, dan kecerdasan buatan.</p>
                </div>
                <div class="hero-card">
                    <div class="hero-card-icon ic-emerald"><i class="bi bi-translate"></i></div>
                    <h4>Bilingual Arab-Inggris</h4>
                    <p>Lingkungan bilingual immersif untuk komunikasi global yang percaya diri.</p>
                </div>
                <div class="hero-card">
                    <div class="hero-card-icon ic-purple"><i class="bi bi-mortarboard-fill"></i></div>
                    <h4>E-Learning Digital</h4>
                    <p>LMS canggih: materi online, kuis interaktif, & pembayaran SPP digital.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── SEJARAH & IDENTITAS ────────────────────────────────────────────────── -->
<section class="section section-alt" id="profil">
    <div class="container">
        <div class="section-header reveal">
            <div class="section-badge"><i class="bi bi-journal-bookmark-fill"></i> Profil Madrasah</div>
            <h2 class="section-title">Mengenal Lebih Dekat <span class="accent">MA Sunan Pandanaran</span></h2>
            <p class="section-subtitle">Berdiri di atas pondasi nilai Islam yang kokoh, kami terus tumbuh menjadi madrasah unggulan bertaraf nasional.</p>
        </div>

        <!-- Sejarah Singkat -->
        <div class="history-block reveal">
            <div class="history-text">
                <div class="section-badge" style="margin-bottom: 1rem;"><i class="bi bi-clock-history"></i> Sejarah Singkat</div>
                <h3 class="history-title">Warisan Pesantren Pandanaran yang Terus Berkembang</h3>
                <p>MA Sunan Pandanaran lahir dari rahim Pesantren Pandanaran yang didirikan oleh <strong>KH. Mufid Mas'ud</strong> pada tahun 1965 di lereng Gunung Merapi, Kabupaten Sleman, D.I. Yogyakarta. Berawal dari kelas sederhana di bawah pohon dengan santri pertama yang hanya berjumlah belasan, madrasah ini tumbuh menjadi salah satu lembaga pendidikan Islam paling bergengsi di kawasan Jawa.</p>
                <p style="margin-top: 0.85rem;">Nama "Sunan Pandanaran" diambil sebagai penghormatan kepada salah satu Wali Songo yang dikenal dengan keistiqamahannya dalam menyebarkan Islam di Nusantara — mencerminkan cita-cita luhur madrasah untuk mencetak generasi Muslim yang berakhlak mulia, berilmu, dan berdedikasi.</p>
                <p style="margin-top: 0.85rem;">Kini, MA Sunan Pandanaran telah meluluskan ribuan alumni yang tersebar di berbagai universitas ternama dalam dan luar negeri, menjadi bukti nyata kualitas pendidikan yang terus dijaga selama lebih dari lima dekade.</p>
                <div class="history-milestones">
                    <div class="milestone">
                        <div class="milestone-year">1965</div>
                        <div class="milestone-event">Berdirinya Pesantren & Madrasah oleh KH. Mufid Mas'ud</div>
                    </div>
                    <div class="milestone">
                        <div class="milestone-year">1985</div>
                        <div class="milestone-event">Ekspansi program dan pembangunan asrama permanen</div>
                    </div>
                    <div class="milestone">
                        <div class="milestone-year">2005</div>
                        <div class="milestone-event">Peraih Akreditasi A pertama & program Tahfidz intensif</div>
                    </div>
                    <div class="milestone">
                        <div class="milestone-year">2024</div>
                        <div class="milestone-event">Digitalisasi LMS & laboratorium AI-Robotika terpadu</div>
                    </div>
                </div>
            </div>
            <div class="history-stats-panel">
                <div class="stats-panel-item">
                    <div class="stats-panel-num">58+</div>
                    <div class="stats-panel-label">Tahun Berdiri</div>
                </div>
                <div class="stats-panel-item">
                    <div class="stats-panel-num">12.000+</div>
                    <div class="stats-panel-label">Alumni Seluruh Indonesia</div>
                </div>
                <div class="stats-panel-item">
                    <div class="stats-panel-num">1.200+</div>
                    <div class="stats-panel-label">Santri Aktif</div>
                </div>
                <div class="stats-panel-item">
                    <div class="stats-panel-num">95+</div>
                    <div class="stats-panel-label">Tenaga Pendidik</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── VISI & MISI ──────────────────────────────────────────────────────────── -->
<section class="section visi-misi-section" id="visi-misi">
    <div class="container">
        <div class="section-header white-text reveal">
            <div class="section-badge" style="background: rgba(251,191,36,0.15); border-color: rgba(251,191,36,0.3); color: #fbbf24;">
                <i class="bi bi-eye-fill"></i> Visi & Misi
            </div>
            <h2 class="section-title">Arah & Tujuan <span style="color: #fbbf24;">Perjuangan Kami</span></h2>
            <p class="section-subtitle">Landasan filosofis yang memandu setiap langkah perjalanan pendidikan MA Sunan Pandanaran.</p>
        </div>
        <div class="visi-misi-grid">
            <!-- Visi -->
            <div class="visi-card reveal">
                <div class="visi-card-header">
                    <div class="visi-icon"><i class="bi bi-stars"></i></div>
                    <h3>Visi Madrasah</h3>
                </div>
                <blockquote class="visi-statement">
                    "Terwujudnya madrasah yang unggul dalam membentuk insan beriman, berilmu, berakhlak mulia, serta berdaya saing global berlandaskan nilai-nilai Al-Qur'an dan Sunnah."
                </blockquote>
            </div>
            <!-- Misi -->
            <div class="misi-card reveal">
                <div class="visi-card-header">
                    <div class="visi-icon" style="background: rgba(251,191,36,0.2); color: #fbbf24;"><i class="bi bi-bullseye"></i></div>
                    <h3>Misi Madrasah</h3>
                </div>
                <ul class="misi-list">
                    <li><i class="bi bi-check-circle-fill"></i> Menyelenggarakan pendidikan berbasis Al-Qur'an dengan program Tahfidz terstruktur dan bersanad</li>
                    <li><i class="bi bi-check-circle-fill"></i> Mengintegrasikan ilmu agama Islam dan ilmu pengetahuan sains-teknologi secara seimbang</li>
                    <li><i class="bi bi-check-circle-fill"></i> Mengembangkan sumber daya pendidik yang profesional, inovatif, dan berjiwa dakwah</li>
                    <li><i class="bi bi-check-circle-fill"></i> Membangun lingkungan bilingual (Arab-Inggris) yang mendukung komunikasi global</li>
                    <li><i class="bi bi-check-circle-fill"></i> Membentuk karakter santri yang mandiri, disiplin, kreatif, dan bertanggung jawab</li>
                    <li><i class="bi bi-check-circle-fill"></i> Menjalin kemitraan strategis dengan universitas, industri, dan lembaga internasional</li>
                </ul>
            </div>
        </div>
        <!-- Nilai Utama -->
        <div class="nilai-grid reveal">
            <div class="nilai-card">
                <div class="nilai-icon">🤲</div>
                <h4>Tauhid</h4>
                <p>Pondasi keimanan yang kuat sebagai pusat seluruh aktivitas pembelajaran</p>
            </div>
            <div class="nilai-card">
                <div class="nilai-icon">📖</div>
                <h4>Ilmu</h4>
                <p>Semangat belajar tanpa henti dalam ilmu agama dan pengetahuan umum</p>
            </div>
            <div class="nilai-card">
                <div class="nilai-icon">⚖️</div>
                <h4>Akhlak</h4>
                <p>Karakter mulia yang menjadi teladan bagi masyarakat dan bangsa</p>
            </div>
            <div class="nilai-card">
                <div class="nilai-icon">🌍</div>
                <h4>Khidmah</h4>
                <p>Dedikasi untuk melayani agama, masyarakat, dan kemajuan negara</p>
            </div>
        </div>
    </div>
</section>

<!-- ─── KEPALA MADRASAH ──────────────────────────────────────────────────────── -->
<section class="section section-alt kepala-section" id="kepala-madrasah">
    <div class="container">
        <div class="section-header reveal">
            <div class="section-badge"><i class="bi bi-person-badge-fill"></i> Pimpinan Madrasah</div>
            <h2 class="section-title">Kepala <span class="accent">Madrasah</span></h2>
            <p class="section-subtitle">Dipimpin oleh figur berpengalaman yang berdedikasi untuk kemajuan pendidikan Islam.</p>
        </div>
        <!-- Kepala Madrasah -->
        <div class="kepala-highlight reveal">
            <div class="kepala-photo-wrapper">
                <div class="kepala-photo-placeholder">
                    <i class="bi bi-person-fill" style="font-size: 5rem; color: rgba(255,255,255,0.6);"></i>
                </div>
                <div class="kepala-badge-tag">Kepala Madrasah</div>
            </div>
            <div class="kepala-bio">
                <div class="section-badge" style="margin-bottom: 1rem;"><i class="bi bi-award-fill"></i> Periode 2022 – Sekarang</div>
                <h3 class="kepala-name">Dr. H. Ahmad Fauzi, M.Pd.</h3>
                <p class="kepala-tagline">"Memimpin dengan hati, mendidik dengan ilmu, dan berjuang dengan istiqamah."</p>
                <div class="kepala-details">
                    <div class="kepala-detail-item">
                        <i class="bi bi-mortarboard-fill"></i>
                        <span><strong>Pendidikan:</strong> S3 Manajemen Pendidikan Islam — UIN Sunan Kalijaga Yogyakarta</span>
                    </div>
                    <div class="kepala-detail-item">
                        <i class="bi bi-briefcase-fill"></i>
                        <span><strong>Pengalaman:</strong> 25+ tahun di bidang pendidikan Islam dan manajemen madrasah</span>
                    </div>
                    <div class="kepala-detail-item">
                        <i class="bi bi-trophy-fill"></i>
                        <span><strong>Prestasi:</strong> Kepala Madrasah Berprestasi Nasional Kemenag RI 2021</span>
                    </div>
                    <div class="kepala-detail-item">
                        <i class="bi bi-book-half"></i>
                        <span><strong>Karya:</strong> Penulis 4 buku pendidikan Islam yang diterbitkan secara nasional</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── PROGRAM UNGGULAN ──────────────────────────────────────────────────── -->
<section class="section section-alt" id="program">
    <div class="container">
        <div class="section-header reveal">
            <div class="section-badge"><i class="bi bi-stars"></i> Program Unggulan</div>
            <h2 class="section-title">Jalur Pembelajaran <span class="accent">Terbaik</span> untuk Generasi Masa Depan</h2>
            <p class="section-subtitle">Kurikulum terintegrasi antara pendidikan agama Islam yang mendalam dan penguasaan sains teknologi modern.</p>
        </div>
        <div class="programs-grid">
            <div class="program-card reveal">
                <div class="program-card-header">
                    <div class="program-icon">📖</div>
                    <h3>Program Tahfidz Al-Qur'an</h3>
                    <p>Kelas Keagamaan Intensif</p>
                </div>
                <div class="program-card-body">
                    <ul class="program-features">
                        <li>Target 30 Juz bil ghoib bersanad</li>
                        <li>Pembina hafizh lulusan Universitas Al-Azhar Kairo</li>
                        <li>Metode mutqin & murajaah terstruktur harian</li>
                        <li>Beasiswa SPP penuh bagi penghafal Al-Qur'an</li>
                        <li>Wisuda Tahfidz tahunan bersama orang tua</li>
                    </ul>
                </div>
            </div>
            <div class="program-card reveal">
                <div class="program-card-header gold">
                    <div class="program-icon">🤖</div>
                    <h3>Sains, Robotika & Informatika</h3>
                    <p>Kelas MIPA Peminatan Teknologi</p>
                </div>
                <div class="program-card-body">
                    <ul class="program-features">
                        <li>Laboratorium AI, robotika & IoT terpadu</li>
                        <li>Kompetisi sains nasional dan internasional</li>
                        <li>Kemitraan riset dengan universitas top Indonesia</li>
                        <li>Pelatihan koding Python, web, dan otomasi</li>
                        <li>Kunjungan industri teknologi dan pusat riset</li>
                    </ul>
                </div>
            </div>
            <div class="program-card reveal">
                <div class="program-card-header teal">
                    <div class="program-icon">🌐</div>
                    <h3>Bilingual Arab & Inggris</h3>
                    <p>Program Internasional Bahasa</p>
                </div>
                <div class="program-card-body">
                    <ul class="program-features">
                        <li>Lingkungan sekolah bilingual Arab-Inggris</li>
                        <li>Nahwu Shorof dan muhadatsah intensif</li>
                        <li>Persiapan IELTS, TOEFL, dan tes JLPT</li>
                        <li>Pertukaran pelajar ke Yordania & Malaysia</li>
                        <li>Drama dan debat Bahasa Arab tingkat nasional</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── PRESTASI ──────────────────────────────────────────────────────────── -->
@if($prestasi->count())
<section class="section" style="background: #f8fafc;">
    <div class="container">
        <div class="section-header reveal">
            <div class="section-badge gold-badge"><i class="bi bi-trophy-fill"></i> Prestasi Gemilang</div>
            <h2 class="section-title">Jejak <span class="accent">Keberhasilan</span> Santri MA Sunan Pandanaran</h2>
        </div>
        <div class="news-grid">
            @foreach($prestasi as $post)
            <a href="{{ route('post.show', $post->slug) }}" class="news-card reveal">
                <div class="news-thumbnail" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                    🏆
                    <span class="news-cat-badge prestasi">Prestasi</span>
                </div>
                <div class="news-body">
                    <div class="news-date"><i class="bi bi-calendar3"></i> {{ $post->published_at?->isoFormat('D MMMM YYYY') }}</div>
                    <div class="news-title">{{ $post->title }}</div>
                    <div class="news-excerpt">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}</div>
                    <span class="read-more">Selengkapnya <i class="bi bi-arrow-right"></i></span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ─── BERITA & AGENDA ────────────────────────────────────────────────────── -->
<section class="section section-alt" id="berita">
    <div class="container">
        <div class="section-header reveal">
            <div class="section-badge"><i class="bi bi-newspaper"></i> Berita & Agenda</div>
            <h2 class="section-title">Kabar Terkini <span class="accent">MA Sunan Pandanaran</span></h2>
            <p class="section-subtitle">Ikuti informasi terbaru seputar kegiatan, pengumuman penting, dan agenda madrasah kami.</p>
        </div>
        <div class="news-grid">
            @forelse($recent_posts as $post)
            <a href="{{ route('post.show', $post->slug) }}" class="news-card reveal">
                <div class="news-thumbnail"
                    style="background: linear-gradient(135deg,
                        {{ $post->category === 'prestasi' ? '#d97706,#f59e0b' : ($post->category === 'agenda' ? '#7c3aed,#8b5cf6' : ($post->category === 'pengumuman' ? '#1d4ed8,#3b82f6' : '#047857,#059669')) }});">
                    {{ $post->category === 'prestasi' ? '🏆' : ($post->category === 'agenda' ? '📅' : ($post->category === 'pengumuman' ? '📢' : '📰')) }}
                    <span class="news-cat-badge {{ $post->category }}">{{ ucfirst($post->category) }}</span>
                </div>
                <div class="news-body">
                    <div class="news-date"><i class="bi bi-calendar3"></i> {{ $post->published_at?->isoFormat('D MMMM YYYY') }}</div>
                    <div class="news-title">{{ $post->title }}</div>
                    <div class="news-excerpt">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 110) }}</div>
                    <span class="read-more">Baca selengkapnya <i class="bi bi-arrow-right"></i></span>
                </div>
            </a>
            @empty
            <div style="grid-column: span 3; text-align: center; padding: 3rem; color: #94a3b8;">
                <i class="bi bi-newspaper" style="font-size: 3rem;"></i>
                <p style="margin-top: 1rem;">Belum ada berita yang dipublikasikan.</p>
            </div>
            @endforelse
        </div>

        <!-- Agenda -->
        @if($agenda->count())
        <div style="margin-top: 3rem;">
            <h3 style="font-size: 1.3rem; font-weight: 800; margin-bottom: 1.5rem; color: #1e293b;">📅 Agenda Mendatang</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                @foreach($agenda as $ag)
                <a href="{{ route('post.show', $ag->slug) }}" class="agenda-card" style="text-decoration: none; background: white; border-radius: 14px; padding: 1.25rem; border: 1px solid #e2e8f0; display: flex; gap: 1rem; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: all 0.3s ease; cursor: pointer;">
                    <div style="background: linear-gradient(135deg, #7c3aed, #8b5cf6); border-radius: 12px; width: 56px; height: 56px; flex-shrink: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; transition: transform 0.3s ease;" class="agenda-date-badge">
                        <div style="font-size: 1.1rem; font-weight: 800; line-height: 1;">{{ $ag->event_date?->format('d') }}</div>
                        <div style="font-size: 0.6rem; text-transform: uppercase;">{{ $ag->event_date?->isoFormat('MMM') }}</div>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 0.95rem; color: #1e293b; transition: color 0.2s;" class="agenda-title">{{ $ag->title }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px; line-height: 1.4;">{{ Str::limit($ag->excerpt ?? strip_tags($ag->content), 80) }}</div>
                        <div style="font-size: 0.75rem; color: #7c3aed; font-weight: 700; margin-top: 8px; opacity: 0; transform: translateX(-10px); transition: all 0.3s ease;" class="agenda-read-more">Lihat Detail <i class="bi bi-arrow-right"></i></div>
                    </div>
                </a>
                @endforeach
            </div>
            
            <style>
                .agenda-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 12px 25px rgba(124, 58, 237, 0.15) !important;
                    border-color: #c4b5fd !important;
                }
                .agenda-card:hover .agenda-date-badge {
                    transform: scale(1.08) rotate(-3deg);
                }
                .agenda-card:hover .agenda-title {
                    color: #7c3aed !important;
                }
                .agenda-card:hover .agenda-read-more {
                    opacity: 1 !important;
                    transform: translateX(0) !important;
                }
            </style>
        </div>
        @endif
    </div>
</section>

<!-- ─── FASILITAS ─────────────────────────────────────────────────────────── -->
<section class="section section-dark" id="fasilitas">
    <div class="container">
        <div class="section-header white-text reveal">
            <div class="section-badge" style="background: rgba(251,191,36,0.15); border-color: rgba(251,191,36,0.3); color: #fbbf24;">
                <i class="bi bi-building-fill"></i> Fasilitas Madrasah
            </div>
            <h2 class="section-title">Infrastruktur <span style="color: #fbbf24;">Modern</span> Berstandar Internasional</h2>
            <p class="section-subtitle">Lingkungan belajar yang kondusif dan canggih untuk mendukung setiap potensi santri.</p>
        </div>
        <div class="facilities-grid">
            <div class="facility-card reveal">
                <div class="facility-icon">🖥️</div>
                <h4>Lab Komputer & AI</h4>
                <p>200 unit komputer high-spec dengan software desain, AI, coding, dan robotika terbaru.</p>
            </div>
            <div class="facility-card reveal">
                <div class="facility-icon">🔬</div>
                <h4>Lab Sains Terpadu</h4>
                <p>Laboratorium fisika, kimia, dan biologi modern dengan instrumen eksperimen standar penelitian.</p>
            </div>
            <div class="facility-card reveal">
                <div class="facility-icon">📚</div>
                <h4>Perpustakaan Digital</h4>
                <p>Koleksi 50.000+ judul buku, e-book, dan akses jurnal ilmiah internasional berbasis digital.</p>
            </div>
            <div class="facility-card reveal">
                <div class="facility-icon">🕌</div>
                <h4>Masjid & Musalla</h4>
                <p>Masjid megah berkapasitas 1.500 jamaah dengan fasilitas wudhu nyaman dan halaman yang asri.</p>
            </div>
            <div class="facility-card reveal">
                <div class="facility-icon">🏠</div>
                <h4>Asrama Boarding Modern</h4>
                <p>Asrama putra-putri terpisah dengan pembina hafizh/hafizhah, AC, dan ruang belajar 24 jam.</p>
            </div>
            <div class="facility-card reveal">
                <div class="facility-icon">⚽</div>
                <h4>Lapangan Multi-Sport</h4>
                <p>Lapangan futsal, basket, bulu tangkis indoor, dan area olahraga outdoor berstandar kompetisi.</p>
            </div>
            <div class="facility-card reveal">
                <div class="facility-icon">🎭</div>
                <h4>Auditorium & Aula</h4>
                <p>Auditorium kapasitas 1.000 kursi dengan sistem audio visual canggih untuk event bergengsi.</p>
            </div>
            <div class="facility-card reveal">
                <div class="facility-icon">🍽️</div>
                <h4>Dapur & Kantin Sehat</h4>
                <p>Kantin dengan standar gizi seimbang halal, menu rotasi bergizi, dan kafetaria santri modern.</p>
            </div>
        </div>
    </div>
</section>

<!-- ─── PPDB ──────────────────────────────────────────────────────────────── -->
<div class="ppdb-section" id="ppdb">
    <div class="ppdb-content">
        <div class="section-badge gold-badge reveal" style="background: rgba(255,255,255,0.25); border-color: rgba(255,255,255,0.4); color: white; margin-bottom: 1.25rem;">
            <i class="bi bi-pencil-square"></i> PPDB 2026/2027 Telah Dibuka
        </div>
        <h2 class="reveal">Bergabunglah Bersama Keluarga Besar MA Sunan Pandanaran</h2>
        <p class="reveal">Daftarkan putra-putri Anda sekarang dan raih masa depan cerah bersama generasi unggul madrasah kami.</p>
        <div class="ppdb-info-grid">
            <div class="ppdb-info-card reveal">
                <div class="icon">🏅</div>
                <h4>Jalur Tahfidz Qur'an</h4>
                <p>Minimal 5 Juz hafalan. Beasiswa penuh SPP + biaya gedung. Bebas tes tertulis.</p>
            </div>
            <div class="ppdb-info-card reveal">
                <div class="icon">🎓</div>
                <h4>Jalur Prestasi Akademik</h4>
                <p>Ranking 1-3 kelas, juara olimpiade, atau nilai UN di atas rata-rata nasional.</p>
            </div>
            <div class="ppdb-info-card reveal">
                <div class="icon">📝</div>
                <h4>Jalur Reguler & CBT</h4>
                <p>Tes seleksi online berbasis komputer. Dua gelombang pendaftaran tersedia.</p>
            </div>
        </div>
        <a href="{{ route('register') }}" class="btn-ppdb-register reveal">
            <i class="bi bi-pencil-square"></i> Daftar Sekarang — Gratis
        </a>
    </div>
</div>

<!-- ─── KONTAK ────────────────────────────────────────────────────────────── -->
<section class="section section-alt" id="kontak">
    <div class="container">
        <div class="section-header reveal">
            <div class="section-badge"><i class="bi bi-envelope-fill"></i> Hubungi Kami</div>
            <h2 class="section-title">Ada Pertanyaan? <span class="accent">Kami Siap Membantu</span></h2>
        </div>
        <div class="contact-grid">
            <div class="contact-info reveal">
                <h3>Informasi Kontak Madrasah</h3>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <h4>Alamat</h4>
                        <p>Jl. Kaliurang KM 14.5, Ngemplak, Sleman,<br>D.I. Yogyakarta 55584</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="bi bi-telephone-fill"></i></div>
                    <div>
                        <h4>Telepon / WhatsApp</h4>
                        <p>(0274) 895-7721<br>+62 812-3456-7890 (PPDB)</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="bi bi-envelope-at-fill"></i></div>
                    <div>
                        <h4>Email Resmi</h4>
                        <p>info@manpandanaran.sch.id<br>ppdb@manpandanaran.sch.id</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="bi bi-clock-fill"></i></div>
                    <div>
                        <h4>Jam Operasional</h4>
                        <p>Senin – Jumat: 07:00 – 16:00 WIB<br>Sabtu: 07:00 – 12:00 WIB</p>
                    </div>
                </div>
            </div>
            <div class="contact-form reveal">
                <h4 style="font-size: 1rem; font-weight: 800; margin-bottom: 1.5rem; color: #1e293b;">📩 Kirim Pesan</h4>
                @if(session('success'))
                    <div class="flash-message flash-success">✅ {{ session('success') }}</div>
                @endif
                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required placeholder="Ahmad Ridwan">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required placeholder="email@example.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nomor WhatsApp</label>
                        <input type="text" name="phone" class="form-control" placeholder="+62 812 xxxx xxxx">
                    </div>
                    <div class="form-group">
                        <label>Keperluan / Subjek</label>
                        <input type="text" name="subject" class="form-control" required placeholder="Pertanyaan mengenai PPDB 2026">
                    </div>
                    <div class="form-group">
                        <label>Isi Pesan</label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="Tuliskan pesan atau pertanyaan Anda di sini..."></textarea>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-send-fill"></i> Kirim Pesan Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
