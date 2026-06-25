<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMataKuliahRequest;
use App\Http\Requests\Admin\UpdateMataKuliahRequest;
use App\Models\MataKuliah;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class MataKuliahController extends Controller
{
    public function index(): View
    {
        $mataKuliahs = MataKuliah::latest()->paginate(10);
        return view('admin.mata_kuliahs.index', compact('mataKuliahs'));
    }

    public function create(): View
    {
        return view('admin.mata_kuliahs.create');
    }

    public function store(StoreMataKuliahRequest $request): RedirectResponse
    {
        MataKuliah::create($request->validated());

        return redirect()->route('admin.mata-kuliahs.index')
            ->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function edit(MataKuliah $mataKuliah): View
    {
        return view('admin.mata_kuliahs.edit', compact('mataKuliah'));
    }

    public function update(UpdateMataKuliahRequest $request, MataKuliah $mataKuliah): RedirectResponse
    {
        $mataKuliah->update($request->validated());

        return redirect()->route('admin.mata-kuliahs.index')
            ->with('success', 'Mata Kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $mataKuliah): RedirectResponse
    {
        $mataKuliah->delete();

        return redirect()->route('admin.mata-kuliahs.index')
            ->with('success', 'Mata Kuliah berhasil dihapus.');
    }
}
