<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * DashboardController — Halaman utama SIAKAD setelah login.
 *
 * Mengambil data mahasiswa (otomatis ter-dekripsi via EncryptedCast)
 * dan menampilkan ringkasan akademik.
 */
final class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Eager-load untuk menghindari N+1 query
        $mahasiswa = Mahasiswa::with(['ipk', 'nilais.mataKuliah'])
            ->where('user_id', $user->id)
            ->first();

        // Jadwal hari ini untuk widget dashboard
        $hariIni = now()->locale('id')->isoFormat('dddd');
        // Normalisasi nama hari ke format database (Senin, Selasa, dst.)
        $hariMap = [
            'Senin'  => 'Senin',
            'Selasa' => 'Selasa',
            'Rabu'   => 'Rabu',
            'Kamis'  => 'Kamis',
            'Jumat'  => 'Jumat',
            'Sabtu'  => 'Sabtu',
        ];
        $hariKey = array_key_first(array_filter($hariMap, fn ($v) => str_starts_with($hariIni, $v))) ?? null;

        $jadwalHariIni = JadwalKuliah::with('mataKuliah')
            ->when($hariKey, fn ($q) => $q->where('hari', $hariKey))
            ->orderBy('jam_mulai')
            ->get();

        $stats = [
            'total_mk'   => $mahasiswa?->nilais?->count() ?? 0,
            'total_sks'  => $mahasiswa?->ipk?->total_sks ?? 0,
            'ipk'        => $mahasiswa?->ipk?->ipk ?? '0.00',
            'semester'   => $mahasiswa?->semester ?? 1,
        ];

        return view('dashboard.index', compact('mahasiswa', 'stats', 'jadwalHariIni'));
    }
}
