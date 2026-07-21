<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJadwalKuliahRequest;
use App\Http\Requests\Admin\UpdateJadwalKuliahRequest;
use App\Models\Dosen;
use App\Models\JadwalKuliah;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class JadwalKuliahController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->has('mahasiswa_id')) {
            $mahasiswa = Mahasiswa::with('user')->findOrFail($request->mahasiswa_id);
            $jadwalKuliahs = JadwalKuliah::with(['mataKuliah', 'dosen', 'ruangan'])->where('mahasiswa_id', $mahasiswa->id)->latest()->paginate(10);
            return view('admin.jadwal_kuliahs.index', compact('jadwalKuliahs', 'mahasiswa'));
        }

        $mahasiswas = Mahasiswa::with('user')->latest()->paginate(10);
        return view('admin.jadwal_kuliahs.index_students', compact('mahasiswas'));
    }

    public function create(Request $request): View
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($request->mahasiswa_id);
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::all();
        $ruangans = Ruangan::all();
        return view('admin.jadwal_kuliahs.create', compact('mahasiswa', 'mataKuliahs', 'dosens', 'ruangans'));
    }

    public function store(StoreJadwalKuliahRequest $request): RedirectResponse
    {
        JadwalKuliah::create($request->validated());

        return redirect()->route('admin.jadwal-kuliahs.index', ['mahasiswa_id' => $request->mahasiswa_id])
            ->with('success', 'Jadwal Kuliah berhasil ditambahkan.');
    }

    public function edit(JadwalKuliah $jadwalKuliah): View
    {
        $mahasiswa = $jadwalKuliah->mahasiswa;
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::all();
        $ruangans = Ruangan::all();
        return view('admin.jadwal_kuliahs.edit', compact('jadwalKuliah', 'mahasiswa', 'mataKuliahs', 'dosens', 'ruangans'));
    }

    public function update(UpdateJadwalKuliahRequest $request, JadwalKuliah $jadwalKuliah): RedirectResponse
    {
        $jadwalKuliah->update($request->validated());

        return redirect()->route('admin.jadwal-kuliahs.index', ['mahasiswa_id' => $jadwalKuliah->mahasiswa_id])
            ->with('success', 'Jadwal Kuliah berhasil diperbarui.');
    }

    public function destroy(JadwalKuliah $jadwalKuliah): RedirectResponse
    {
        $mahasiswaId = $jadwalKuliah->mahasiswa_id;
        $jadwalKuliah->delete();

        return redirect()->route('admin.jadwal-kuliahs.index', ['mahasiswa_id' => $mahasiswaId])
            ->with('success', 'Jadwal Kuliah berhasil dihapus.');
    }
}
