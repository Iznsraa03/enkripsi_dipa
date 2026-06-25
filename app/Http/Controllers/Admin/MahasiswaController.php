<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMahasiswaRequest;
use App\Http\Requests\Admin\UpdateMahasiswaRequest;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

final class MahasiswaController extends Controller
{
    public function index(): View
    {
        $mahasiswas = Mahasiswa::with('user')->latest()->paginate(10);
        return view('admin.mahasiswas.index', compact('mahasiswas'));
    }

    public function create(): View
    {
        return view('admin.mahasiswas.create');
    }

    public function store(StoreMahasiswaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $user = User::create([
                'nim'      => $data['nim'],
                'password' => Hash::make($data['password']),
                'role'     => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id'       => $user->id,
                'nama'          => $data['nama'],
                'email'         => $data['email'],
                'alamat'        => $data['alamat'],
                'nomor_telepon' => $data['nomor_telepon'],
                'program_studi' => $data['program_studi'],
                'semester'      => $data['semester'],
                'angkatan'      => $data['angkatan'],
            ]);
        });

        return redirect()->route('admin.mahasiswas.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa): View
    {
        $mahasiswa->load('user');
        return view('admin.mahasiswas.edit', compact('mahasiswa'));
    }

    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $mahasiswa) {
            $userUpdates = ['nim' => $data['nim']];
            if (!empty($data['password'])) {
                $userUpdates['password'] = Hash::make($data['password']);
            }
            $mahasiswa->user->update($userUpdates);

            $mahasiswa->update([
                'nama'          => $data['nama'],
                'email'         => $data['email'],
                'alamat'        => $data['alamat'],
                'nomor_telepon' => $data['nomor_telepon'],
                'program_studi' => $data['program_studi'],
                'semester'      => $data['semester'],
                'angkatan'      => $data['angkatan'],
            ]);
        });

        return redirect()->route('admin.mahasiswas.index')
            ->with('success', 'Profil mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa): RedirectResponse
    {
        DB::transaction(function () use ($mahasiswa) {
            // Because cascadeOnDelete is set in migration, 
            // deleting User will also delete Mahasiswa, Nilai, Ipk.
            $mahasiswa->user->delete();
        });

        return redirect()->route('admin.mahasiswas.index')
            ->with('success', 'Mahasiswa beserta akunnya berhasil dihapus.');
    }
}
