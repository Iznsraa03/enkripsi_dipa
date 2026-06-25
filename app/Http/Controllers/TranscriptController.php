<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * TranscriptController — Transkrip nilai dan IPK mahasiswa.
 *
 * Nilai `nilai_angka` dan `ipk` otomatis ter-dekripsi via EncryptedCast
 * tanpa perlu memanggil EncryptionService secara eksplisit.
 */
final class TranscriptController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Eager-load nilais dengan mataKuliah untuk menghindari N+1
        $mahasiswa = Mahasiswa::with(['nilais.mataKuliah', 'ipk'])
            ->where('user_id', $user->id)
            ->first();

        // Nilai ter-dekripsi otomatis oleh EncryptedCast
        $nilais = $mahasiswa?->nilais ?? collect();

        // Hitung statistik per semester
        $nilaiPerSemester = $nilais->groupBy('semester_tahun');

        $ipk   = $mahasiswa?->ipk?->ipk ?? '0.00';
        $totalSks = $mahasiswa?->ipk?->total_sks ?? 0;

        return view('transcript.index', compact(
            'mahasiswa',
            'nilais',
            'nilaiPerSemester',
            'ipk',
            'totalSks'
        ));
    }
}
