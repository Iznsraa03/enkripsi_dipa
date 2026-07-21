@extends('layouts.app')

@section('title', 'Kelola Nilai: ' . $mahasiswa->nama)
@section('page_title', 'Kelola Nilai: ' . $mahasiswa->nama . ' (' . ($mahasiswa->user->nim ?? '') . ')')

@section('content')

@if(session('success'))
<div class="alert alert-success mb-4">
    <span class="material-symbols-outlined">check_circle</span>
    {{ session('success') }}
</div>
@endif

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ sim_route('admin.nilais.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="padding: 4px 8px;">
                <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
            </a>
            <div class="card-header-title mb-0">
                <span class="material-symbols-outlined fill">grade</span>
                Daftar Nilai
            </div>
        </div>
        <a href="{{ sim_route('admin.nilais.create', ['mahasiswa_id' => $mahasiswa->id]) }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Input Nilai
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Nilai Angka</th>
                        <th>Grade</th>
                        <th>Periode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $nilai)
                    <tr>
                        <td>
                            <strong>{{ $nilai->mataKuliah->nama_mk }}</strong><br>
                            <span class="text-muted" style="font-size:11px;">{{ $nilai->mataKuliah->kode_mk }}</span>
                        </td>
                        <td>{{ $nilai->mataKuliah->sks }}</td>
                        <td>
                            {{-- Field nilai_angka di-decrypt otomatis karena EncryptedCast --}}
                            <strong>{{ $nilai->nilai_angka }}</strong>
                        </td>
                        <td>
                            @php
                                $gClass = '';
                                if(str_starts_with($nilai->grade, 'A')) $gClass = 'grade-a';
                                elseif(str_starts_with($nilai->grade, 'B')) $gClass = 'grade-b';
                                elseif(str_starts_with($nilai->grade, 'C')) $gClass = 'grade-c';
                                else $gClass = 'grade-d';
                            @endphp
                            <span class="{{ $gClass }}">{{ $nilai->grade }}</span>
                        </td>
                        <td>{{ $nilai->semester_tahun }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ sim_route('admin.nilais.edit', $nilai) }}" class="btn btn-sm btn-secondary" title="Edit">
                                    <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                </a>
                                <form action="{{ sim_route('admin.nilais.destroy', $nilai) }}" method="POST" onsubmit="return confirm('Hapus nilai ini? Penghapusan akan memicu kalkulasi ulang IPK secara otomatis.');">
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
                        <td colspan="6" class="text-center py-4">Belum ada data nilai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($nilais->hasPages())
    <div class="card-footer">
        {{ $nilais->links() }}
    </div>
    @endif
</div>

@endsection
