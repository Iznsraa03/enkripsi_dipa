<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * ScheduleController — Jadwal kuliah mahasiswa.
 */
final class ScheduleController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        // Ambil semua jadwal dengan eager-load mata kuliah
        $jadwals = JadwalKuliah::with('mataKuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_mulai')
            ->get();

        // Kelompokkan per hari untuk tampilan yang lebih rapi
        $jadwalPerHari = $jadwals->groupBy('hari');

        return view('schedule.index', compact('mahasiswa', 'jadwals', 'jadwalPerHari'));
    }
}
