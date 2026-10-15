<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\FeeType;
use App\Models\StudentBill;
use App\Models\Payment;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Classes
        $class1 = SchoolClass::create([
            'name' => 'X MIPA 1',
            'academic_year' => '2025/2026',
            'homeroom_teacher_name' => 'Ahmad Dahlan, S.Pd.I',
        ]);

        $class2 = SchoolClass::create([
            'name' => 'X MIPA 2',
            'academic_year' => '2025/2026',
            'homeroom_teacher_name' => 'Siti Nurhaliza, M.Pd',
        ]);

        $class3 = SchoolClass::create([
            'name' => 'XI IPS 1',
            'academic_year' => '2025/2026',
            'homeroom_teacher_name' => 'Drs. H. Bambang Subagyo',
        ]);

        $class4 = SchoolClass::create([
            'name' => 'XII Keagamaan',
            'academic_year' => '2025/2026',
            'homeroom_teacher_name' => 'Ustadzah Fatimah Azzahra, Lc.',
        ]);

        // 2. Create Users (Admin, Teachers, Students, Treasurer)
        $admin = User::create([
            'name' => 'Dr. H. Ahmad Fauzi, M.Pd',
            'email' => 'admin@manpandanaran.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'identity_number' => '197505122000031002',
            'phone' => '081234567890',
            'gender' => 'L',
            'address' => 'Komplek Islamic Center No. 12, Sleman, D.I. Yogyakarta',
        ]);

        $bendahara = User::create([
            'name' => 'Hj. Rohmah Wardani, S.E.',
            'email' => 'bendahara@manpandanaran.sch.id',
            'password' => Hash::make('password'),
            'role' => 'bendahara',
            'identity_number' => '198208152006042005',
            'phone' => '081298765432',
            'gender' => 'P',
            'address' => 'Jl. Kaliurang KM 12.5, Sleman',
        ]);

        $guru1 = User::create([
            'name' => 'Ust. Ahmad Dahlan, S.Pd.I',
            'email' => 'guru@manpandanaran.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'identity_number' => '198503102009121003',
            'phone' => '081322334455',
            'gender' => 'L',
            'address' => 'Jl. Pandanaran No. 45, Sleman',
        ]);

        $guru2 = User::create([
            'name' => 'Siti Nurhaliza, M.Pd',
            'email' => 'guru2@manpandanaran.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'identity_number' => '198907212014022004',
            'phone' => '081355667788',
            'gender' => 'P',
            'address' => 'Jl. Damai No. 8, Ngaglik',
        ]);

        $guru3 = User::create([
            'name' => 'Ustadzah Fatimah Azzahra, Lc.',
            'email' => 'guru3@manpandanaran.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'identity_number' => '199211052018012006',
            'phone' => '081377889900',
            'gender' => 'P',
            'address' => 'Jl. Palagan Tentara Pelajar KM 9',
        ]);

        $siswa1 = User::create([
            'name' => 'Muhammad Fatih Al-Ayyubi',
            'email' => 'siswa@manpandanaran.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'identity_number' => '0081234567',
            'phone' => '085711223344',
            'gender' => 'L',
            'class_id' => $class1->id,
            'address' => 'Jl. Candi Gebang Blok C-14, Sleman',
        ]);

        $siswa2 = User::create([
            'name' => 'Aisyah Khairunnisa',
            'email' => 'siswa2@manpandanaran.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'identity_number' => '0087654321',
            'phone' => '085799887766',
            'gender' => 'P',
            'class_id' => $class1->id,
            'address' => 'Perum Candi Indah No. 5, Ngemplak',
        ]);

        $siswa3 = User::create([
            'name' => 'Rizky Ramadhan',
            'email' => 'siswa3@manpandanaran.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'identity_number' => '0078899001',
            'phone' => '085812345678',
            'gender' => 'L',
            'class_id' => $class3->id,
            'address' => 'Jl. Gejayan No. 22, Condongcatur',
        ]);

        // 3. Create Subjects
        $subFikih = Subject::create([
            'code' => 'FIQ-10',
            'name' => 'Fikih Madrasah',
            'category' => 'keagamaan',
            'icon' => 'book-open',
        ]);

        $subQuran = Subject::create([
            'code' => 'QUR-10',
            'name' => 'Al-Qur\'an Hadits',
            'category' => 'keagamaan',
            'icon' => 'bookmark-heart',
        ]);

        $subArab = Subject::create([
            'code' => 'ARB-10',
            'name' => 'Bahasa Arab',
            'category' => 'keagamaan',
            'icon' => 'translate',
        ]);

        $subMtk = Subject::create([
            'code' => 'MTK-10',
            'name' => 'Matematika Peminatan',
            'category' => 'umum',
            'icon' => 'calculator',
        ]);

        $subFisika = Subject::create([
            'code' => 'FIS-10',
            'name' => 'Fisika Sains',
            'category' => 'peminatan',
            'icon' => 'atom',
        ]);

        $subInformatika = Subject::create([
            'code' => 'INF-10',
            'name' => 'Informatika & Robotika',
            'category' => 'peminatan',
            'icon' => 'cpu',
        ]);

        // 4. Create Courses (Assign Subjects & Teachers to Class)
        $course1 = Course::create([
            'class_id' => $class1->id,
            'subject_id' => $subFikih->id,
            'teacher_id' => $guru1->id,
            'day' => 'Senin',
            'time_start' => '07:30',
            'time_end' => '09:00',
            'room' => 'Ruang 101',
            'description' => 'Mempelajari hukum-hukum syariat Islam terkait ibadah, muamalah kontemporer, dan ekonomi syariah.',
        ]);

        $course2 = Course::create([
            'class_id' => $class1->id,
            'subject_id' => $subMtk->id,
            'teacher_id' => $guru2->id,
            'day' => 'Selasa',
            'time_start' => '09:15',
            'time_end' => '11:00',
            'room' => 'Lab Sains & Komputer',
            'description' => 'Eksplorasi konsep matematika terapan, logika algoritma, aljabar, dan kalkulus dasar.',
        ]);

        $course3 = Course::create([
            'class_id' => $class1->id,
            'subject_id' => $subArab->id,
            'teacher_id' => $guru3->id,
            'day' => 'Kamis',
            'time_start' => '08:00',
            'time_end' => '09:30',
            'room' => 'Lab Bahasa Terpadu',
            'description' => 'Pengembangan kemampuan tata bahasa Nahwu Shorof, percakapan (Muhadatsah), dan literatur Arab.',
        ]);

        $course4 = Course::create([
            'class_id' => $class1->id,
            'subject_id' => $subInformatika->id,
            'teacher_id' => $guru2->id,
            'day' => 'Jumat',
            'time_start' => '08:00',
            'time_end' => '10:00',
            'room' => 'Lab Robotik & Multimedia',
            'description' => 'Pemrograman web, kecerdasan buatan dasar, otomasi IoT, dan perancangan sistem cerdas madrasah.',
        ]);

        // 5. Create Materials for Course 1 (Fikih)
        Material::create([
            'course_id' => $course1->id,
            'title' => 'Bab 1: Konsep Akad dan Prinsip Muamalah Kontemporer',
            'content' => "Muamalah adalah aturan syariat Islam yang mengatur hubungan antar manusia dalam urusan kebendaan dan perdataan.\n\nPrinsip Utama Muamalah:\n1. Hukum asal dalam muamalah adalah mubah (boleh) kecuali ada dalil yang melarangnya.\n2. Dilakukan atas dasar kerelaan kedua belah pihak ('An Taradhin).\n3. Mendatangkan maslahat dan menolak kemudharatan.\n4. Terbebas dari riba, gharar (ketidakpastian/penipuan), dan maysir (judi/spekulasi berlebihan).\n5. Menjaga keadilan dan transparansi dalam transaksi digital maupun konvensional.",
            'file_path' => null,
            'file_type' => 'link',
            'external_link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'created_by' => $guru1->id,
        ]);

        Material::create([
            'course_id' => $course1->id,
            'title' => 'Bab 2: Fiqih Zakat Mal & Implementasi Zakat Digital',
            'content' => "Zakat merupakan rukun Islam ketiga yang memiliki dimensi spiritual dan sosial ekonomi. Zakat mal wajib dikeluarkan oleh setiap muslim yang memiliki harta mencapai nishab dan haul.",
            'file_path' => null,
            'file_type' => 'pdf',
            'external_link' => null,
            'created_by' => $guru1->id,
        ]);

        // Materials for Course 2 (Matematika)
        Material::create([
            'course_id' => $course2->id,
            'title' => 'Modul 1: Fungsi Kuadrat dan Grafiknya',
            'content' => "Fungsi kuadrat memiliki bentuk umum f(x) = ax^2 + bx + c dengan a != 0. Diskriminan D = b^2 - 4ac menentukan titik potong terhadap sumbu X.",
            'file_path' => null,
            'file_type' => 'pdf',
            'external_link' => null,
            'created_by' => $guru2->id,
        ]);

        // 6. Create Assignments
        $assign1 = Assignment::create([
            'course_id' => $course1->id,
            'title' => 'Tugas 1: Studi Kasus Transaksi E-Commerce dan PayLater dalam Perspektif Fikih',
            'description' => "Silakan buat esai ringkas (minimal 500 kata) yang mengkaji mekanisme fitur Buy Now Pay Later (BNPL) pada marketplace modern menurut pandangan fatwa DSN-MUI dan para ulama fiqih kontemporer.\n\nPoin yang harus dianalisis:\n1. Skema akad (qardh, bai' muajjal, atau murabahah)\n2. Unsur biaya denda keterlambatan dan tambahan bunga\n3. Rekomendasi solusi transaksi syariah yang aman",
            'file_attachment' => null,
            'due_date' => now()->addDays(7),
            'max_score' => 100,
            'created_by' => $guru1->id,
        ]);

        $assign2 = Assignment::create([
            'course_id' => $course2->id,
            'title' => 'Tugas Mandiri: Penyelesaian Soal Persamaan Matriks 3x3',
            'description' => "Kerjakan lembar kerja 5 butir soal matriks determinan dan invers menggunakan metode eliminasi Gauss-Jordan. Unggah foto atau scan lembar jawaban dalam format PDF/JPG.",
            'file_attachment' => null,
            'due_date' => now()->addDays(5),
            'max_score' => 100,
            'created_by' => $guru2->id,
        ]);

        // Sample submission by student 1
        AssignmentSubmission::create([
            'assignment_id' => $assign1->id,
            'student_id' => $siswa1->id,
            'file_path' => 'submissions/sample_tugas_fikih.pdf',
            'student_note' => 'Bismillah, berikut naskah analisis tugas fikih muamalah saya mengenai akad e-commerce dan paylater, Ustadz.',
            'score' => 95,
            'teacher_feedback' => 'Analisis sangat mendalam, dalil-dalil DSN-MUI yang dikutip sangat relevan dan terstruktur. Pertahankan!',
            'status' => 'graded',
            'submitted_at' => now()->subDay(),
        ]);

        // 7. Create Interactive Quizzes
        $quiz1 = Quiz::create([
            'course_id' => $course1->id,
            'title' => 'Penilaian Harian 1: Fikih Muamalah & Akad Syariah',
            'description' => 'Kuis online menguji pemahaman dasar prinsip jual beli, rukun akad, dan larangan transaksi terlarang dalam Islam. Durasi waktu 15 menit.',
            'duration_minutes' => 15,
            'due_date' => now()->addDays(10),
            'passing_score' => 75,
            'is_published' => true,
            'created_by' => $guru1->id,
        ]);

        // Questions for Quiz 1
        QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'Kaidah fiqih menyatakan: "Al-ashlu fil mu\'amalati al-ibahah hatta yadulla ad-dalilu \'ala tahrimiha". Apa arti kaidah tersebut?',
            'option_a' => 'Hukum asal dalam muamalah adalah haram sampai ada dalil yang membolehkannya',
            'option_b' => 'Hukum asal dalam muamalah adalah mubah (boleh) hingga ada dalil yang mengharamkannya',
            'option_c' => 'Semua bentuk transaksi jual beli harus seizin pemerintah',
            'option_d' => 'Ibadah dan muamalah memiliki aturan yang sama persis dalam dalil',
            'correct_option' => 'B',
            'score_weight' => 20,
            'explanation' => 'Kaidah pokok muamalah memberikan kelonggaran berinovasi dalam transaksi ekonomi selama tidak melanggar batasan syariat seperti riba, gharar, dan maisir.',
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'Unsur ketidakpastian, kesamaran informasi barang, atau manipulasi yang dapat merugikan salah satu pihak dalam transaksi disebut...',
            'option_a' => 'Riba Nasi\'ah',
            'option_b' => 'Maysir',
            'option_c' => 'Gharar',
            'option_d' => 'Tadlis',
            'correct_option' => 'C',
            'score_weight' => 20,
            'explanation' => 'Gharar adalah kondisi di mana barang atau objek akad mengandung ketidakjelasan yang membahayakan transaksi.',
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'Transaksi jual beli di mana penjual menyerahkan barang terlebih dahulu dan pembeli membayar secara bertahap atau ditangguhkan disebut akad...',
            'option_a' => 'Bai\' al-Murabahah / Bai\' Bi Tsaman \'Ajil',
            'option_b' => 'Ijarah',
            'option_c' => 'Wakalah Bil Ujrah',
            'option_d' => 'Musyarakah Mutanaqisah',
            'correct_option' => 'A',
            'score_weight' => 20,
            'explanation' => 'Bai\' bi tsaman \'ajil atau murabahah cicilan adalah jual beli dengan pembayaran tangguh atau bertahap.',
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'Rukun jual beli dalam fiqih Islam terdiri dari hal-hal berikut, KECUALI...',
            'option_a' => 'Pihak yang berakad (Penjual & Pembeli / \'Aqidani)',
            'option_b' => 'Objek barang atau jasa yang diperjualbelikan (Ma\'qud \'Alaih)',
            'option_c' => 'Ijab dan Qabul (Shighat)',
            'option_d' => 'Adanya jaminan sertifikat tanah bermaterai',
            'correct_option' => 'D',
            'score_weight' => 20,
            'explanation' => 'Sertifikat tanah bukanlah rukun mutlak dalam fiqih, melainkan syarat administratif tambahan pada transaksi tertentu.',
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'Kadar nishab zakat emas yang telah mencapai haul (1 tahun kepemilikan) adalah...',
            'option_a' => '85 gram emas murni',
            'option_b' => '50 gram emas murni',
            'option_c' => '100 gram emas murni',
            'option_d' => '75 gram emas murni',
            'correct_option' => 'A',
            'score_weight' => 20,
            'explanation' => 'Nishab zakat emas adalah 20 Dinar atau setara 85 gram emas murni dengan kadar zakat 2,5%.',
        ]);

        // Quiz 2 (Matematika)
        $quiz2 = Quiz::create([
            'course_id' => $course2->id,
            'title' => 'Kuis Kilat: Logika & Aljabar',
            'description' => 'Uji ketangkasan berpikir logis dan kalkulasi dasar aljabar.',
            'duration_minutes' => 10,
            'due_date' => now()->addDays(8),
            'passing_score' => 70,
            'is_published' => true,
            'created_by' => $guru2->id,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz2->id,
            'question' => 'Jika akar-akar persamaan x^2 - 5x + 6 = 0 adalah p dan q, maka nilai p + q adalah...',
            'option_a' => '5',
            'option_b' => '-5',
            'option_c' => '6',
            'option_d' => '-6',
            'correct_option' => 'A',
            'score_weight' => 50,
            'explanation' => 'Berdasarkan rumus Vieta: p + q = -b/a = -(-5)/1 = 5.',
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz2->id,
            'question' => 'Nilai dari sin(30°) + cos(60°) adalah...',
            'option_a' => '0.5',
            'option_b' => '1',
            'option_c' => '1.5',
            'option_d' => '0',
            'correct_option' => 'B',
            'score_weight' => 50,
            'explanation' => 'sin(30°) = 1/2 dan cos(60°) = 1/2. Jadi 1/2 + 1/2 = 1.',
        ]);

        // 8. Create Attendance
        $att1 = Attendance::create([
            'course_id' => $course1->id,
            'title' => 'Pertemuan 1: Pengantar Prinsip Muamalah Islam',
            'date' => now()->subDays(7)->toDateString(),
            'notes' => 'Pembahasan silabus semester dan kontrak belajar.',
        ]);

        AttendanceRecord::create([
            'attendance_id' => $att1->id,
            'student_id' => $siswa1->id,
            'status' => 'hadir',
            'note' => 'Hadir tepat waktu',
        ]);

        AttendanceRecord::create([
            'attendance_id' => $att1->id,
            'student_id' => $siswa2->id,
            'status' => 'hadir',
            'note' => 'Hadir tepat waktu',
        ]);

        // 9. Create Fee Types (Jenis Biaya Madrasah)
        $feeSpp = FeeType::create([
            'name' => 'SPP Bulanan',
            'code' => 'SPP',
            'default_amount' => 250000,
            'is_monthly' => true,
            'description' => 'Sumbangan Pembinaan Pendidikan bulanan untuk kegiatan operasional madrasah & sarana belajar.',
        ]);

        $feeGedung = FeeType::create([
            'name' => 'Dana Pengembangan Fasilitas & Lab Madrasah',
            'code' => 'GEDUNG',
            'default_amount' => 1500000,
            'is_monthly' => false,
            'description' => 'Biaya penunjang laboratorium robotik, sains terpadu, perpustakaan digital, dan smart classroom.',
        ]);

        $feeSeragam = FeeType::create([
            'name' => 'Paket Seragam & Atribut Madrasah',
            'code' => 'SERAGAM',
            'default_amount' => 650000,
            'is_monthly' => false,
            'description' => 'Seragam batik khas MA Sunan Pandanaran, jas almamater, seragam olahraga, dan atribut madrasah.',
        ]);

        $feeUjian = FeeType::create([
            'name' => 'Asesmen & Ujian Sumatif Semester',
            'code' => 'UJIAN',
            'default_amount' => 200000,
            'is_monthly' => false,
            'description' => 'Biaya operasional pelaksanaan asesmen berbasis CBT dan evaluasi komprehensif.',
        ]);

        // 10. Create Student Bills for Siswa 1 (Muhammad Fatih)
        // Bill 1: SPP September 2026 (Unpaid)
        $bill1 = StudentBill::create([
            'bill_code' => 'INV-202609-001',
            'student_id' => $siswa1->id,
            'fee_type_id' => $feeSpp->id,
            'title' => 'SPP Madrasah - September 2026',
            'month' => 9,
            'year' => 2026,
            'amount' => 250000,
            'due_date' => now()->addDays(15)->toDateString(),
            'status' => 'unpaid',
        ]);

        // Bill 2: SPP Agustus 2026 (Paid via QRIS)
        $bill2 = StudentBill::create([
            'bill_code' => 'INV-202608-001',
            'student_id' => $siswa1->id,
            'fee_type_id' => $feeSpp->id,
            'title' => 'SPP Madrasah - Agustus 2026',
            'month' => 8,
            'year' => 2026,
            'amount' => 250000,
            'due_date' => now()->subDays(15)->toDateString(),
            'status' => 'paid',
        ]);

        Payment::create([
            'payment_code' => 'PAY-20260810-8891',
            'bill_id' => $bill2->id,
            'amount_paid' => 250000,
            'payment_method' => 'qris',
            'va_number' => 'QRIS-NMID-936005210988',
            'proof_file' => null,
            'notes' => 'Pembayaran via QRIS Dinamis Otomatis Terverifikasi',
            'status' => 'success',
            'paid_at' => now()->subDays(18),
            'verified_at' => now()->subDays(18),
            'verified_by' => $bendahara->id,
        ]);

        // Bill 3: Seragam (Pending Verification)
        $bill3 = StudentBill::create([
            'bill_code' => 'INV-2026-SRG-001',
            'student_id' => $siswa1->id,
            'fee_type_id' => $feeSeragam->id,
            'title' => 'Paket Seragam & Atribut Siswa Baru',
            'month' => null,
            'year' => 2026,
            'amount' => 650000,
            'due_date' => now()->addDays(20)->toDateString(),
            'status' => 'pending',
        ]);

        Payment::create([
            'payment_code' => 'PAY-20260908-4122',
            'bill_id' => $bill3->id,
            'amount_paid' => 650000,
            'payment_method' => 'manual_transfer',
            'va_number' => null,
            'proof_file' => 'proofs/sample_transfer_receipt.jpg',
            'notes' => 'Transfer via BSI a.n. Ayah Muhammad Fatih',
            'status' => 'pending',
            'paid_at' => now()->subHours(5),
            'verified_at' => null,
            'verified_by' => null,
        ]);

        // Bill 4: Dana Gedung for Siswa 2
        StudentBill::create([
            'bill_code' => 'INV-2026-GDG-002',
            'student_id' => $siswa2->id,
            'fee_type_id' => $feeGedung->id,
            'title' => 'Dana Fasilitas & Lab Madrasah 2026',
            'month' => null,
            'year' => 2026,
            'amount' => 1500000,
            'due_date' => now()->addDays(30)->toDateString(),
            'status' => 'unpaid',
        ]);

        // 11. Create Posts (Berita, Agenda, Prestasi, Pengumuman)
        Post::create([
            'title' => 'Tim Riset & Robotika MA Sunan Pandanaran Raih Medali Emas di Ajang International Science Expo 2026',
            'slug' => 'tim-robotika-man-pandanaran-raih-emas-2026',
            'category' => 'prestasi',
            'excerpt' => 'Karya inovasi sistem irigasi pintar berbasis IoT dan energi surya karya santri MA Sunan Pandanaran berhasil memukau dewan juri internasional.',
            'content' => "Prestasi membanggakan kembali diukir oleh civitas akademika **Madrasah Aliyah Negeri (MAN) Pandanaran**. Dalam ajang bergengsi *International Science and Invention Expo 2026*, tim robotika madrasah berhasil mempersembahkan Medali Emas (Gold Medal) setelah menyisihkan lebih dari 250 tim perwakilan dari 18 negara.\n\nInovasi yang diusung bertajuk *'Smart Eco-Irrigation for Modern Agriculture'* yang menggabungkan sensor tanah real-time, energi surya terbarukan, dan aplikasi cerdas pemantau kelembaban. Kepala Madrasah, Dr. H. Ahmad Fauzi, M.Pd, menyampaikan apresiasi setinggi-tingginya kepada para siswa pembina yang terus berjuang mengharumkan nama bangsa di kancah global.\n\n\"Madrasah Mandiri Berprestasi bukan sekadar slogan, melainkan tekad nyata santri kita dalam membuktikan bahwa nilai-nilai keislaman dan kemajuan sains teknologi dapat berpadu secara harmonis,\" tutur beliau.",
            'thumbnail' => null,
            'author_id' => $admin->id,
            'event_date' => null,
            'is_featured' => true,
            'published_at' => now()->subDays(2),
        ]);

        Post::create([
            'title' => 'Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran 2026/2027 Resmi Dibuka: Jalur Prestasi & Reguler',
            'slug' => 'ppdb-man-pandanaran-2026-2027',
            'category' => 'pengumuman',
            'excerpt' => 'Informasi lengkap pendaftaran santri baru program Unggulan Tahfidz, Kelas Sains Robotika, dan Kelas Internasional Bahasa.',
            'content' => "MA Sunan Pandanaran secara resmi membuka pendaftaran Peserta Didik Baru (PPDB) untuk Tahun Ajaran 2026/2027.\n\n### Jalur Pendaftaran:\n1. **Jalur Prestasi Akademik & Non-Akademik**: Dibuka s.d. 30 April 2026\n2. **Jalur Khusus Tahfidz Qur'an (Minimal 5 Juz)**: Bebas tes akademik dan beasiswa SPP\n3. **Jalur Reguler / CBT Online**: Pendaftaran gelombang I dan II\n\n### Fasilitas Unggulan:\n- Asrama Pesantren Modern (Boarding School)\n- Laboratorium Komputer AI & Robotika\n- Perpustakaan Digital Berstandar Nasional\n- Kerjasama Program Luar Negeri (Middle East & Asia)",
            'thumbnail' => null,
            'author_id' => $admin->id,
            'event_date' => null,
            'is_featured' => true,
            'published_at' => now()->subDays(5),
        ]);

        Post::create([
            'title' => 'Wisuda Akbar Tahfidzul Qur\'an 30 Juz & Khotmil Kutub Angkatan XIV',
            'slug' => 'wisuda-tahfidz-30-juz-angkatan-xiv',
            'category' => 'agenda',
            'excerpt' => 'Sebanyak 42 santri berhasil menuntaskan hafalan 30 Juz bil ghoib dan diwisuda bersama para orang tua di Auditorium Utama.',
            'content' => "Suasana haru dan khidmat menyelimuti Auditorium Utama MA Sunan Pandanaran dalam perhelatan Wisuda Akbar Tahfidzul Qur'an Angkatan XIV. Kegiatan ini menjadi wujud komitmen madrasah dalam melahirkan generasi qur'ani yang berakhlak mulia dan berwawasan ilmiah luas.",
            'thumbnail' => null,
            'author_id' => $admin->id,
            'event_date' => now()->addDays(14)->toDateString(),
            'is_featured' => false,
            'published_at' => now()->subDays(8),
        ]);

        Post::create([
            'title' => 'Kunjungan Studi Inspirasi dan Kolaborasi Riset ke Pusat Riset Tenaga Nuklir & Sains Terapan',
            'slug' => 'kunjungan-studi-riset-2026',
            'category' => 'berita',
            'excerpt' => 'Siswa kelas XI MIPA memperdalam pemahaman fisika kuantum dan teknologi radiasi pangan.',
            'content' => "Dalam rangka memperkaya wawasan aplikatif sains, 80 santri peminatan MIPA MA Sunan Pandanaran melaksanakan kunjungan edukatif ke pusat riset sains terkemuka. Santri diberikan kesempatan mengamati langsung instrumen laboratorium mutakhir serta berdialog langsung dengan para peneliti.",
            'thumbnail' => null,
            'author_id' => $admin->id,
            'event_date' => null,
            'is_featured' => false,
            'published_at' => now()->subDays(12),
        ]);
    }
}
