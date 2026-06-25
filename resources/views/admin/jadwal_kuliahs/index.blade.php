@extends('layouts.app')

@section('title', 'Kelola Jadwal Kuliah')
@section('page_title', 'Kelola Jadwal Kuliah')

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
            <span class="material-symbols-outlined fill">calendar_month</span>
            Daftar Jadwal Kuliah
        </div>
        <a href="{{ route('admin.jadwal-kuliahs.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Tambah Jadwal
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>Hari</th>
                        <th>Waktu</th>
                        <th>Ruangan</th>
                        <th>Dosen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwalKuliahs as $jadwal)
                    <tr>
                        <td>
                            <strong>{{ $jadwal->mataKuliah->nama_mk }}</strong><br>
                            <span class="text-muted" style="font-size:11px;">{{ $jadwal->mataKuliah->kode_mk }}</span>
                        </td>
                        <td>{{ $jadwal->hari }}</td>
                        <td>{{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</td>
                        <td>{{ $jadwal->ruangan }}</td>
                        <td>{{ $jadwal->dosen }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.jadwal-kuliahs.edit', $jadwal) }}" class="btn btn-sm btn-secondary" title="Edit">
                                    <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                </a>
                                <form action="{{ route('admin.jadwal-kuliahs.destroy', $jadwal) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
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
                        <td colspan="6" class="text-center py-4">Belum ada jadwal kuliah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($jadwalKuliahs->hasPages())
    <div class="card-footer">
        {{ $jadwalKuliahs->links() }}
    </div>
    @endif
</div>

@endsection
