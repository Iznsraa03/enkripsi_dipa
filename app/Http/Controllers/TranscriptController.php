<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

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

    /**
     * Memverifikasi PIN input pengguna terhadap PIN terenkripsi di DB.
     */
    public function verifyPin(Request $request): JsonResponse
    {
        $request->validate([
            'pin' => 'required|string|min:4|max:6',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // user->pin otomatis didekripsi dari AES-256-GCM oleh EncryptedCast
        if (empty($user->pin) || $user->pin !== $request->input('pin')) {
            return response()->json([
                'success' => false,
                'message' => 'PIN verifikasi yang Anda masukkan tidak cocok.'
            ], 422);
        }

        // Simpan waktu verifikasi PIN sukses di Session (berlaku 10 menit)
        session()->put('transcript_pin_verified_at', now());

        return response()->json([
            'success' => true,
            'message' => 'PIN berhasil diverifikasi.'
        ]);
    }

    /**
     * Mengunduh file transkrip resmi berformat HTML.
     */
    public function download()
    {
        $verifiedAt = session()->get('transcript_pin_verified_at');

        if (!$verifiedAt || now()->diffInMinutes($verifiedAt) > 10) {
            return redirect()->route('transcript')
                ->withErrors(['pin' => 'Sesi verifikasi PIN telah kedaluwarsa atau belum dilakukan.']);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $mahasiswa = Mahasiswa::with(['nilais.mataKuliah', 'ipk'])
            ->where('user_id', $user->id)
            ->first();

        if (!$mahasiswa) {
            abort(404, 'Data mahasiswa tidak ditemukan.');
        }

        $nilais = $mahasiswa->nilais ?? collect();
        $ipk    = $mahasiswa->ipk?->ipk ?? '0.00';
        $totalSks = $mahasiswa->ipk?->total_sks ?? 0;

        $filename = 'Transkrip_Resmi_' . str_replace(' ', '_', $mahasiswa->nama) . '_' . $user->nim . '.html';

        $htmlContent = view('transcript.download_template', compact(
            'mahasiswa',
            'nilais',
            'ipk',
            'totalSks'
        ))->render();

        // Hapus session verifikasi setelah selesai download untuk alasan keamanan
        session()->forget('transcript_pin_verified_at');

        return response($htmlContent)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
