@extends('layouts.app')

@section('title', 'Kelola Mata Kuliah')
@section('page_title', 'Kelola Mata Kuliah')

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
            <span class="material-symbols-outlined fill">book</span>
            Daftar Mata Kuliah
        </div>
        <a href="{{ sim_route('admin.mata-kuliahs.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Tambah Mata Kuliah
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th>Jenis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mataKuliahs as $mk)
                    <tr>
                        <td><strong>{{ $mk->kode_mk }}</strong></td>
                        <td>{{ $mk->nama_mk }}</td>
                        <td>{{ $mk->sks }}</td>
                        <td>{{ $mk->semester }}</td>
                        <td>
                            @if($mk->jenis == 'wajib')
                                <span class="badge badge-success">Wajib</span>
                            @else
                                <span class="badge badge-secondary">Pilihan</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ sim_route('admin.mata-kuliahs.edit', $mk) }}" class="btn btn-sm btn-secondary" title="Edit">
                                    <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                </a>
                                <form action="{{ sim_route('admin.mata-kuliahs.destroy', $mk) }}" method="POST" onsubmit="return confirm('Hapus mata kuliah ini?');">
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
                        <td colspan="6" class="text-center py-4">Belum ada mata kuliah terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($mataKuliahs->hasPages())
    <div class="card-footer">
        {{ $mataKuliahs->links() }}
    </div>
    @endif
</div>

@endsection
