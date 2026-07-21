<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * AuthenticateMahasiswa Middleware.
 *
 * Memastikan hanya user yang telah login yang dapat mengakses
 * rute yang dilindungi. Redirect ke /login jika belum terautentikasi.
 */
final class AuthenticateMahasiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            // Jika request berasal dari prefix /simulasi, redirect ke login simulasi
            $loginRoute = str_starts_with($request->path(), 'simulasi')
                ? route('simulasi.login')
                : route('login');

            return redirect($loginRoute)
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
