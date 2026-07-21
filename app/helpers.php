<?php

declare(strict_types=1);

if (! function_exists('sim_route')) {
    /**
     * Generate URL untuk route yang aware terhadap mode simulasi.
     *
     * Jika mode simulasi aktif (config('app.simulation') === true),
     * maka nama route akan diberi prefix 'simulasi.' secara otomatis.
     *
     * Contoh:
     *   sim_route('dashboard')            → /simulasi/dashboard  (saat simulasi)
     *   sim_route('admin.nilais.index')   → /simulasi/admin/nilais  (saat simulasi)
     *   sim_route('dashboard')            → /dashboard  (saat normal)
     *
     * @param  string $name       Nama route (tanpa prefix simulasi)
     * @param  mixed  $parameters Parameter route
     * @param  bool   $absolute   Apakah menghasilkan URL absolut
     * @return string
     */
    function sim_route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        if (config('app.simulation') === true) {
            // Tambahkan prefix simulasi. jika belum ada
            $prefixed = str_starts_with($name, 'simulasi.') ? $name : 'simulasi.' . $name;
            return route($prefixed, $parameters, $absolute);
        }

        return route($name, $parameters, $absolute);
    }
}
