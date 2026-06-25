<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNilaiRequest;
use App\Http\Requests\Admin\UpdateNilaiRequest;
use App\Models\Ipk;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class NilaiController extends Controller
{
    public function index(): View
    {
        $nilais = Nilai::with(['mahasiswa', 'mataKuliah'])->latest()->paginate(10);
        return view('admin.nilais.index', compact('nilais'));
    }

    public function create(): View
    {
        $mahasiswas = Mahasiswa::all();
        $mataKuliahs = MataKuliah::all();
        return view('admin.nilais.create', compact('mahasiswas', 'mataKuliahs'));
    }

    public function store(StoreNilaiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['grade'] = $this->calculateGrade((float) $data['nilai_angka']);

        DB::transaction(function () use ($data) {
            Nilai::create($data);
            Ipk::recalculateForMahasiswa((int) $data['mahasiswa_id']);
        });

        return redirect()->route('admin.nilais.index')
            ->with('success', 'Nilai berhasil ditambahkan dan IPK diperbarui.');
    }

    public function edit(Nilai $nilai): View
    {
        $mahasiswas = Mahasiswa::all();
        $mataKuliahs = MataKuliah::all();
        return view('admin.nilais.edit', compact('nilai', 'mahasiswas', 'mataKuliahs'));
    }

    public function update(UpdateNilaiRequest $request, Nilai $nilai): RedirectResponse
    {
        $data = $request->validated();
        $data['grade'] = $this->calculateGrade((float) $data['nilai_angka']);

        DB::transaction(function () use ($data, $nilai) {
            $nilai->update($data);
            Ipk::recalculateForMahasiswa((int) $data['mahasiswa_id']);
        });

        return redirect()->route('admin.nilais.index')
            ->with('success', 'Nilai berhasil diperbarui dan IPK diperbarui.');
    }

    public function destroy(Nilai $nilai): RedirectResponse
    {
        $mahasiswaId = $nilai->mahasiswa_id;

        DB::transaction(function () use ($nilai, $mahasiswaId) {
            $nilai->delete();
            Ipk::recalculateForMahasiswa($mahasiswaId);
        });

        return redirect()->route('admin.nilais.index')
            ->with('success', 'Nilai berhasil dihapus dan IPK diperbarui.');
    }

    private function calculateGrade(float $score): string
    {
        if ($score >= 85) return 'A';
        if ($score >= 80) return 'A-';
        if ($score >= 75) return 'B+';
        if ($score >= 70) return 'B';
        if ($score >= 65) return 'B-';
        if ($score >= 60) return 'C+';
        if ($score >= 55) return 'C';
        if ($score >= 40) return 'D';
        return 'E';
    }
}
