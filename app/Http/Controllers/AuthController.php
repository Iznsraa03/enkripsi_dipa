<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * AuthController — Mengelola autentikasi Argon2id SIAKAD.
 *
 * Menggunakan Laravel's Auth facade yang dikonfigurasi dengan driver Argon2id.
 * Menerapkan:
 *  - Session regeneration (mencegah session fixation attack)
 *  - Flash messages untuk feedback login
 *  - Throttle dapat ditambahkan via middleware di routes
 */
final class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi.
     *
     * Flow:
     * 1. Validasi input via LoginRequest (NIM format + password min length)
     * 2. Auth::attempt() — Laravel verifikasi password vs Argon2id hash di DB
     * 3. Jika berhasil: regenerate session ID (cegah session fixation)
     * 4. Redirect ke dashboard
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = [
            'nim'      => $request->validated('nim'),
            'password' => $request->validated('password'),
        ];

        if (Auth::attempt($credentials, remember: false)) {
            // Session regeneration — mencegah session fixation attack
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Selamat datang, Administrator!');
            }

            return redirect()
                ->route('dashboard')
                ->with('success', 'Selamat datang di SIAKAD Enkripsi!');
        }

        return back()
            ->withInput($request->only('nim'))
            ->withErrors([
                'nim' => 'NIM atau password salah. Silakan coba lagi.',
            ]);
    }

    /**
     * Proses logout dan invalidasi session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
