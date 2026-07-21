<?php

use App\Http\Middleware\AuthenticateMahasiswa;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Jalankan SimulationMiddleware paling pertama di web group agar koneksi beralih sebelum Route Model Binding (SubstituteBindings)
        $middleware->prependToGroup('web', \App\Http\Middleware\SimulationMiddleware::class);

        // Alias untuk middleware autentikasi SIAKAD
        $middleware->alias([
            'auth.mahasiswa' => AuthenticateMahasiswa::class,
            'auth.admin'     => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

