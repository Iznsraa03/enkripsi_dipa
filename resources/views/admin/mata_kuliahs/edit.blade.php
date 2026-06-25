@extends('layouts.app')

@section('title', 'Edit Mata Kuliah')
@section('page_title', 'Edit Mata Kuliah')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="card-header-title">
            <span class="material-symbols-outlined fill">edit_document</span>
            Edit Mata Kuliah: {{ $mataKuliah->kode_mk }}
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.mata-kuliahs.update', $mataKuliah) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-2 gap-3 mb-4">
                <div class="form-group mb-0">
                    <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
                    <input type="text" name="kode_mk" id="kode_mk" class="form-control @error('kode_mk') is-invalid @enderror" value="{{ old('kode_mk', $mataKuliah->kode_mk) }}" required>
                    @error('kode_mk') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
                    <input type="text" name="nama_mk" id="nama_mk" class="form-control @error('nama_mk') is-invalid @enderror" value="{{ old('nama_mk', $mataKuliah->nama_mk) }}" required>
                    @error('nama_mk') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-3 gap-3 mb-4">
                <div class="form-group mb-0">
                    <label for="sks" class="form-label">SKS</label>
                    <input type="number" name="sks" id="sks" class="form-control @error('sks') is-invalid @enderror" value="{{ old('sks', $mataKuliah->sks) }}" min="1" max="10" required>
                    @error('sks') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="semester" class="form-label">Semester</label>
                    <input type="number" name="semester" id="semester" class="form-control @error('semester') is-invalid @enderror" value="{{ old('semester', $mataKuliah->semester) }}" min="1" max="14" required>
                    @error('semester') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="jenis" class="form-label">Jenis</label>
                    <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                        <option value="wajib" {{ old('jenis', $mataKuliah->jenis) == 'wajib' ? 'selected' : '' }}>Wajib</option>
                        <option value="pilihan" {{ old('jenis', $mataKuliah->jenis) == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                    </select>
                    @error('jenis') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.mata-kuliahs.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
