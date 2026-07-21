@extends('layouts.app')

@section('title', 'Pilih Mahasiswa (Kelola Nilai)')
@section('page_title', 'Pilih Mahasiswa (Kelola Nilai)')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-title">
            <span class="material-symbols-outlined fill">group</span>
            Daftar Mahasiswa
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Program Studi</th>
                        <th>Semester</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $mhs)
                    <tr>
                        <td><strong>{{ $mhs->user->nim ?? '-' }}</strong></td>
                        <td>{{ $mhs->nama }}</td>
                        <td>{{ $mhs->program_studi }}</td>
                        <td>{{ $mhs->semester }}</td>
                        <td>
                            <a href="{{ sim_route('admin.nilais.index', ['mahasiswa_id' => $mhs->id]) }}" class="btn btn-sm btn-primary d-flex align-items-center gap-1" style="width:fit-content;">
                                <span class="material-symbols-outlined" style="font-size:16px;">grade</span>
                                Kelola Nilai
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada data mahasiswa.</td>
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
