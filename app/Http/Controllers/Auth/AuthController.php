<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login Admin.
     * Wajib menampilkan form autentikasi dan tidak melakukan bypass/auto-redirect ke panel admin.
     */
    public function showLogin(Request $request): View
    {
        // Jika ada sesi login aktif saat membuka halaman login, bersihkan sesi
        // agar pengguna wajib memasukkan kredensial autentikasi untuk masuk ke panel admin.
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi Admin dengan sesi aman dan proteksi CSRF.
     */
    public function login(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('admin.dashboard'),
                ]);
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Berhasil masuk sebagai Administrator.');
        }

        $errorMessage = 'Alamat email atau kata sandi tidak sesuai.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
            ], 422);
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['auth' => $errorMessage]);
    }

    /**
     * Keluar dari sesi Admin secara aman.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Sesi admin telah ditutup.');
    }
}
