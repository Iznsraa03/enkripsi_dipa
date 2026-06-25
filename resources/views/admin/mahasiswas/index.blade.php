@extends('layouts.app')

@section('title', 'Kelola Mahasiswa')
@section('page_title', 'Kelola Profil Mahasiswa')

@section('content')

@if(session('success'))
<div class="alert alert-success mb-4">
    <span class="material-symbols-outlined">check_circle</span>
    {{ session('success') }}
</div>
@endif

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-title">
            <span class="material-symbols-outlined fill">person</span>
            Daftar Profil Mahasiswa
        </div>
        <a href="{{ route('admin.mahasiswas.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Tambah Mahasiswa
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama (Decrypted)</th>
                        <th>Prodi</th>
                        <th>Semester</th>
                        <th>Angkatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $mhs)
                    <tr>
                        <td><strong>{{ $mhs->user->nim ?? '-' }}</strong></td>
                        <td>
                            <div class="d-flex flex-column">
                                <span>{{ $mhs->nama }}</span>
                                <span class="text-muted" style="font-size:11px;">{{ $mhs->email }}</span>
                            </div>
                        </td>
                        <td>{{ $mhs->program_studi }}</td>
                        <td>{{ $mhs->semester }}</td>
                        <td>{{ $mhs->angkatan }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.mahasiswas.edit', $mhs) }}" class="btn btn-sm btn-secondary" title="Edit">
                                    <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                </a>
                                <form action="{{ route('admin.mahasiswas.destroy', $mhs) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini? Menghapus mahasiswa juga akan menghapus akun user, nilai, dan IPK secara permanen.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada profil mahasiswa terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($mahasiswas->hasPages())
    <div class="card-footer">
        {{ $mahasiswas->links() }}
    </div>
    @endif
</div>

@endsection
