<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LmsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExamCardController;
use App\Http\Controllers\PaymentController;

// ─── ROOT → redirect ke login ─────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'))->name('landing');

// Midtrans Webhook Notification Callback
Route::post('/pembayaran/midtrans/notification', [PaymentController::class, 'midtransNotification'])->name('payment.midtrans.notification');

// ─── AUTH ─────────────────────────────────────────────────────────────────────
Route::get('/pendaftaran', [AuthController::class, 'showRegister'])->name('register');
Route::post('/pendaftaran', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/lupa-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/lupa-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── AUTHENTICATED ROUTES ─────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    // Kartu Ujian Siswa
    Route::get('/kartu-ujian', [ExamCardController::class, 'studentCard'])->name('exam_cards.student');

    // ─── LMS ─────────────────────────────────────────────────────────────────
    Route::prefix('lms')->name('lms.')->group(function () {

        // Courses
        Route::get('/kelas', [LmsController::class, 'courses'])->name('courses');
        Route::get('/kelas/{id}', [LmsController::class, 'showCourse'])->name('course.show');

        // Materials (Guru only)
        Route::get('/kelas/{courseId}/materi/tambah', [LmsController::class, 'createMaterial'])
            ->middleware('role:guru,admin')->name('material.create');
        Route::post('/kelas/{courseId}/materi', [LmsController::class, 'storeMaterial'])
            ->middleware('role:guru,admin')->name('material.store');

        // Assignments
        Route::get('/kelas/{courseId}/tugas/buat', [LmsController::class, 'createAssignment'])
            ->middleware('role:guru,admin')->name('assignment.create');
        Route::post('/kelas/{courseId}/tugas', [LmsController::class, 'storeAssignment'])
            ->middleware('role:guru,admin')->name('assignment.store');
        Route::get('/tugas/{id}', [LmsController::class, 'showAssignment'])->name('assignment.show');
        Route::post('/tugas/{id}/kumpul', [LmsController::class, 'submitAssignment'])
            ->middleware('role:siswa')->name('assignment.submit');
        Route::post('/tugas/nilai/{submissionId}', [LmsController::class, 'gradeSubmission'])
            ->middleware('role:guru,admin')->name('assignment.grade');

        // Quizzes
        Route::get('/kuis/{id}', [LmsController::class, 'showQuiz'])->name('quiz.show');
        Route::get('/kuis/{id}/mulai', [LmsController::class, 'startQuiz'])
            ->middleware('role:siswa')->name('quiz.start');
        Route::post('/kuis/{id}/kumpul', [LmsController::class, 'submitQuiz'])
            ->middleware('role:siswa')->name('quiz.submit');
        Route::get('/kuis/hasil/{attemptId}', [LmsController::class, 'quizResult'])->name('quiz.result');

        // Attendance
        Route::get('/kelas/{courseId}/presensi', [LmsController::class, 'attendance'])->name('attendance');
        Route::post('/kelas/{courseId}/presensi', [LmsController::class, 'storeAttendance'])
            ->middleware('role:guru,admin')->name('attendance.store');

        // Exam Grades (UTS & UAS)
        Route::get('/kelas/{courseId}/nilai-ujian', [LmsController::class, 'showExamGrades'])->name('exam_grades');
        Route::post('/kelas/{courseId}/nilai-ujian', [LmsController::class, 'storeExamGrades'])
            ->middleware('role:guru,admin')->name('exam_grades.store');
    });

    // ─── PAYMENT (Siswa & Bendahara) ──────────────────────────────────────────────
    Route::prefix('pembayaran')->name('payment.')->group(function () {

        // Student
        Route::get('/tagihan', [PaymentController::class, 'studentBills'])
            ->middleware('role:siswa')->name('student.bills');
        Route::get('/bayar/{billId}', [PaymentController::class, 'showPayForm'])
            ->middleware('role:siswa')->name('student.pay');
        Route::post('/bayar/{billId}', [PaymentController::class, 'processPayment'])
            ->middleware('role:siswa')->name('student.process');
        Route::get('/konfirmasi/{paymentId}', [PaymentController::class, 'confirmation'])
            ->middleware('role:siswa')->name('student.confirmation');
        Route::get('/kwitansi/{paymentId}', [PaymentController::class, 'receipt'])->name('student.receipt');

        // Bendahara / Admin
        Route::get('/keuangan', [PaymentController::class, 'financeIndex'])
            ->middleware('role:bendahara,admin')->name('finance.index');
        Route::post('/keuangan/verifikasi/{paymentId}', [PaymentController::class, 'verifyPayment'])
            ->middleware('role:bendahara,admin')->name('finance.verify');
        Route::get('/keuangan/generate-tagihan', [PaymentController::class, 'generateBills'])
            ->middleware('role:bendahara,admin')->name('finance.generate');
        Route::post('/keuangan/generate-tagihan', [PaymentController::class, 'storeBills'])
            ->middleware('role:bendahara,admin')->name('finance.storeBills');
        Route::get('/keuangan/laporan', [PaymentController::class, 'financeReport'])
            ->middleware('role:bendahara,admin')->name('finance.report');
    });

    // ─── ADMIN ───────────────────────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {

        // User management
        Route::get('/pengguna', [AdminController::class, 'users'])->name('users');
        Route::get('/pengguna/tambah', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/pengguna', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/pengguna/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/pengguna/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/pengguna/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

        // Post management
        Route::get('/berita', [AdminController::class, 'posts'])->name('posts');
        Route::get('/berita/tambah', [AdminController::class, 'createPost'])->name('posts.create');
        Route::post('/berita', [AdminController::class, 'storePost'])->name('posts.store');
        Route::delete('/berita/{id}', [AdminController::class, 'deletePost'])->name('posts.delete');

        // Class management
        Route::get('/kelas', [AdminController::class, 'classes'])->name('classes');
        Route::post('/kelas', [AdminController::class, 'storeClass'])->name('classes.store');

        // PPDB Registration management
        Route::get('/ppdb', [AdminController::class, 'registrations'])->name('registrations');
        Route::post('/ppdb/{id}/approve', [AdminController::class, 'approveRegistration'])->name('registrations.approve');
        Route::post('/ppdb/{id}/reject', [AdminController::class, 'rejectRegistration'])->name('registrations.reject');
        Route::delete('/ppdb/{id}', [AdminController::class, 'deleteRegistration'])->name('registrations.delete');

        // Contact Messages
        Route::get('/pesan', [AdminController::class, 'messages'])->name('messages');
        Route::delete('/pesan/{id}', [AdminController::class, 'deleteMessage'])->name('messages.delete');

        // Exam Cards management
        Route::get('/kartu-ujian', [ExamCardController::class, 'adminIndex'])->name('exam_cards');
        Route::post('/kartu-ujian', [ExamCardController::class, 'adminStoreOrUpdate'])->name('exam_cards.store');
        Route::post('/kartu-ujian/generate', [ExamCardController::class, 'adminGenerate'])->name('exam_cards.generate');
        Route::delete('/kartu-ujian/{id}', [ExamCardController::class, 'adminDelete'])->name('exam_cards.delete');
    });
});
