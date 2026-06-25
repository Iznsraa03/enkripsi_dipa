@extends('layouts.app')

@section('title', 'Edit Nilai')
@section('page_title', 'Edit Nilai Mahasiswa')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="card-header-title">
            <span class="material-symbols-outlined fill">edit_document</span>
            Edit Nilai: {{ $nilai->mahasiswa->nama }} - {{ $nilai->mataKuliah->kode_mk }}
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.nilais.update', $nilai) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-2 gap-3 mb-4">
                <div class="form-group mb-0">
                    <label for="mahasiswa_id" class="form-label">Mahasiswa</label>
                    <select name="mahasiswa_id" id="mahasiswa_id" class="form-control @error('mahasiswa_id') is-invalid @enderror" required>
                        @foreach($mahasiswas as $mhs)
                            <option value="{{ $mhs->id }}" {{ old('mahasiswa_id', $nilai->mahasiswa_id) == $mhs->id ? 'selected' : '' }}>
                                {{ $mhs->user->nim ?? '-' }} - {{ $mhs->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('mahasiswa_id') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="mata_kuliah_id" class="form-label">Mata Kuliah</label>
                    <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-control @error('mata_kuliah_id') is-invalid @enderror" required>
                        @foreach($mataKuliahs as $mk)
                            <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $nilai->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>
                                {{ $mk->kode_mk }} - {{ $mk->nama_mk }} ({{ $mk->sks }} SKS)
                            </option>
                        @endforeach
                    </select>
                    @error('mata_kuliah_id') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-2 gap-3 mb-4">
                <div class="form-group mb-0">
                    <label for="nilai_angka" class="form-label">Nilai Angka (0-100)</label>
                    <input type="number" step="0.01" name="nilai_angka" id="nilai_angka" class="form-control @error('nilai_angka') is-invalid @enderror" value="{{ old('nilai_angka', $nilai->nilai_angka) }}" min="0" max="100" required>
                    @error('nilai_angka') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="semester_tahun" class="form-label">Periode / Semester</label>
                    <input type="text" name="semester_tahun" id="semester_tahun" class="form-control @error('semester_tahun') is-invalid @enderror" value="{{ old('semester_tahun', $nilai->semester_tahun) }}" required>
                    @error('semester_tahun') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.nilais.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
