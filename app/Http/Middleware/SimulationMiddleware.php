<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * SimulationMiddleware
 *
 * Mengaktifkan mode simulasi SIAKAD tanpa enkripsi sama sekali:
 *   - Koneksi database dialihkan ke `mysql_simulation` (prefix tabel: `sim_`)
 *   - AES-256-GCM dinonaktifkan (EncryptedCast menyimpan dan membaca plaintext)
 *   - Argon2id diganti dengan Bcrypt untuk hashing password
 *   - Verifikasi algoritma hash Bcrypt dinonaktifkan agar kompatibel dengan hash
 *     lama (Argon2id) yang mungkin masih tersimpan di tabel simulasi
 *
 * Semua URL (redirect & HTML link/action) diintersep agar tetap berada
 * di bawah prefix /simulasi/ selama sesi simulasi berlangsung.
 */
final class SimulationMiddleware
{
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        // Hanya jalankan jika request mengarah ke prefix /simulasi
        if (!$request->is('simulasi') && !$request->is('simulasi/*')) {
            return $next($request);
        }

        // 1. Aktifkan flag simulasi — bypass EncryptedCast
        config(['app.simulation' => true]);

        // 2. Ganti hash driver ke Bcrypt dan matikan strict algorithm verification
        config([
            'hashing.driver'        => 'bcrypt',
            'hashing.bcrypt.verify' => false,
        ]);

        // 3. Switch default DB connection ke mysql_simulation (tabel: sim_*)
        config(['database.default' => 'mysql_simulation']);
        DB::setDefaultConnection('mysql_simulation');
        
        // Purge koneksi utama agar dipaksa me-resolve ulang bila dipanggil
        DB::purge('mysql');
        DB::purge('mysql_simulation');
        DB::reconnect('mysql_simulation');

        /** @var SymfonyResponse $response */
        $response = $next($request);

        // 4. Rewrite redirect agar tetap di bawah /simulasi/
        if ($response instanceof RedirectResponse) {
            $targetUrl  = $response->getTargetUrl();
            $parsedPath = parse_url($targetUrl, PHP_URL_PATH);

            // Tambahkan prefix /simulasi jika path belum ada dan bukan asset
            if ($parsedPath && ! str_starts_with($parsedPath, '/simulasi') && ! str_starts_with($parsedPath, '/assets')) {
                $baseUrl = rtrim(config('app.url'), '/');
                $newUrl  = $baseUrl . '/simulasi' . $parsedPath;

                // Pertahankan query string jika ada
                $query = parse_url($targetUrl, PHP_URL_QUERY);
                if ($query) {
                    $newUrl .= '?' . $query;
                }

                $response->setTargetUrl($newUrl);
            }
        }

        // 5. Rewrite HTML links agar tetap di bawah /simulasi/
        if ($response instanceof Response) {
            $content     = $response->getContent();
            $contentType = $response->headers->get('Content-Type', '');

            if (is_string($content) && str_contains($contentType, 'text/html')) {
                // Rewrite href= dan action= yang mengarah ke path non-simulasi
                $content = preg_replace(
                    '/(href|action)="(\/(?!simulasi\/|assets\/)[^"]+)"/',
                    '$1="/simulasi$2"',
                    $content
                );

                $response->setContent($content);
            }
        }

        return $response;
    }
}
