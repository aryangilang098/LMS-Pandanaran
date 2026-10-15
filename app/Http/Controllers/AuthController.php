<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Registration;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function showForgotPassword()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Direct request message saved for Admin to review in Admin Panel
        ContactMessage::create([
            'name'    => $user ? $user->name : 'Permohonan Lupa Password',
            'email'   => $request->email,
            'phone'   => $user ? $user->phone : null,
            'subject' => '🔐 Permohonan Reset Password LMS',
            'message' => 'Permohonan reset password diterima untuk email: ' . $request->email . 
                         ($user ? " (Nama: {$user->name}, Role: " . ucfirst($user->role) . ", NIS/NIP: {$user->identity_number})" : " (Alamat email tidak ditemukan di data akun aktif, mohon verifikasi data siswa/guru).") .
                         ". Mohon Admin IT meninjau dan mereset kata sandi akun terkait.",
            'status'  => 'unread',
        ]);

        return back()->with('success', 'Permohonan reset password telah berhasil dikirimkan langsung ke Admin IT Madrasah. Admin akan meninjau pesan Anda dan segera menghubungi atau memproses reset akun Anda.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nisn'     => 'required|string|max:20|unique:registrations,nisn',
            'phone'    => 'required|string|max:20',
            'school'   => 'required|string|max:255',
            'program'  => 'required|in:tahfidz,sains,bilingual,reguler',
            'email'    => 'required|email|unique:registrations,email|unique:users,email',
            'password' => 'required|min:8',
        ], [
            'email.unique'  => 'Email ini sudah terdaftar.',
            'nisn.unique'   => 'NISN ini sudah terdaftar.',
            'password.min'  => 'Password minimal 8 karakter.',
        ]);

        Registration::create([
            'name'     => $request->name,
            'nisn'     => $request->nisn,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'school'   => $request->school,
            'program'  => $request->program,
            'password' => Hash::make($request->password),
            'status'   => 'pending',
        ]);

        return back()->with('success', 'Pendaftaran PPDB berhasil diajukan! Silakan tunggu konfirmasi dari panitia melalui WhatsApp/Email Anda.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}
