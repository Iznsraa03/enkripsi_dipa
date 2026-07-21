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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class NilaiController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->has('mahasiswa_id')) {
            $mahasiswa = Mahasiswa::with('user')->findOrFail($request->mahasiswa_id);
            $nilais = Nilai::with(['mataKuliah'])->where('mahasiswa_id', $mahasiswa->id)->latest()->paginate(10);
            return view('admin.nilais.index', compact('nilais', 'mahasiswa'));
        }

        $mahasiswas = Mahasiswa::with('user')->latest()->paginate(10);
        return view('admin.nilais.index_students', compact('mahasiswas'));
    }

    public function create(Request $request): View
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($request->mahasiswa_id);
        $mataKuliahs = MataKuliah::all();
        return view('admin.nilais.create', compact('mahasiswa', 'mataKuliahs'));
    }

    public function store(StoreNilaiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['grade'] = $this->calculateGrade((float) $data['nilai_angka']);

        DB::transaction(function () use ($data) {
            Nilai::create($data);
            Ipk::recalculateForMahasiswa((int) $data['mahasiswa_id']);
        });

        return redirect()->route('admin.nilais.index', ['mahasiswa_id' => $data['mahasiswa_id']])
            ->with('success', 'Nilai berhasil ditambahkan dan IPK diperbarui.');
    }

    public function edit(Nilai $nilai): View
    {
        $mahasiswa = $nilai->mahasiswa;
        $mataKuliahs = MataKuliah::all();
        return view('admin.nilais.edit', compact('nilai', 'mahasiswa', 'mataKuliahs'));
    }

    public function update(UpdateNilaiRequest $request, Nilai $nilai): RedirectResponse
    {
        $data = $request->validated();
        $data['grade'] = $this->calculateGrade((float) $data['nilai_angka']);

        DB::transaction(function () use ($data, $nilai) {
            $nilai->update($data);
            Ipk::recalculateForMahasiswa((int) $data['mahasiswa_id']);
        });

        return redirect()->route('admin.nilais.index', ['mahasiswa_id' => $data['mahasiswa_id']])
            ->with('success', 'Nilai berhasil diperbarui dan IPK diperbarui.');
    }

    public function destroy(Nilai $nilai): RedirectResponse
    {
        $mahasiswaId = $nilai->mahasiswa_id;

        DB::transaction(function () use ($nilai, $mahasiswaId) {
            $nilai->delete();
            Ipk::recalculateForMahasiswa($mahasiswaId);
        });

        return redirect()->route('admin.nilais.index', ['mahasiswa_id' => $mahasiswaId])
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
