<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\EncryptionService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Binding EncryptionService sebagai singleton sehingga key AES
     * hanya dibaca dari config sekali dan di-share ke seluruh aplikasi.
     */
    public function register(): void
    {
        $this->app->singleton(EncryptionService::class, fn () => new EncryptionService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
