<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKuliah;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\User;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function index(): View
    {
        // Compute overall IPK simply by querying all Nilai
        // For a more accurate global average, we might average the `ipk` column in `ipks` table.
        $totalIpk = \App\Models\Ipk::avg('ipk');

        $stats = [
            'total_users'        => User::count(),
            'total_mahasiswas'   => Mahasiswa::count(),
            'total_mata_kuliahs' => MataKuliah::count(),
            'total_jadwal'       => JadwalKuliah::count(),
            'total_nilai'        => Nilai::count(),
            'avg_ipk'            => number_format((float) $totalIpk, 2),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
