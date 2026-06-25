<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJadwalKuliahRequest;
use App\Http\Requests\Admin\UpdateJadwalKuliahRequest;
use App\Models\JadwalKuliah;
use App\Models\MataKuliah;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class JadwalKuliahController extends Controller
{
    public function index(): View
    {
        $jadwalKuliahs = JadwalKuliah::with('mataKuliah')->latest()->paginate(10);
        return view('admin.jadwal_kuliahs.index', compact('jadwalKuliahs'));
    }

    public function create(): View
    {
        $mataKuliahs = MataKuliah::all();
        return view('admin.jadwal_kuliahs.create', compact('mataKuliahs'));
    }

    public function store(StoreJadwalKuliahRequest $request): RedirectResponse
    {
        JadwalKuliah::create($request->validated());

        return redirect()->route('admin.jadwal-kuliahs.index')
            ->with('success', 'Jadwal Kuliah berhasil ditambahkan.');
    }

    public function edit(JadwalKuliah $jadwalKuliah): View
    {
        $mataKuliahs = MataKuliah::all();
        return view('admin.jadwal_kuliahs.edit', compact('jadwalKuliah', 'mataKuliahs'));
    }

    public function update(UpdateJadwalKuliahRequest $request, JadwalKuliah $jadwalKuliah): RedirectResponse
    {
        $jadwalKuliah->update($request->validated());

        return redirect()->route('admin.jadwal-kuliahs.index')
            ->with('success', 'Jadwal Kuliah berhasil diperbarui.');
    }

    public function destroy(JadwalKuliah $jadwalKuliah): RedirectResponse
    {
        $jadwalKuliah->delete();

        return redirect()->route('admin.jadwal-kuliahs.index')
            ->with('success', 'Jadwal Kuliah berhasil dihapus.');
    }
}
