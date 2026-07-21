@extends('layouts.app')

@section('title', 'Tambah Jadwal: ' . $mahasiswa->nama)
@section('page_title', 'Tambah Jadwal: ' . $mahasiswa->nama)

@section('content')

<div class="card">
    <div class="card-header">
        <div class="card-header-title">
            <span class="material-symbols-outlined fill">event</span>
            Input Jadwal Baru
        </div>
    </div>
    <div class="card-body">
        <form action="{{ sim_route('admin.jadwal-kuliahs.store') }}" method="POST">
            @csrf
            
            <div class="form-group mb-3">
                <label class="form-label">Mahasiswa</label>
                <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id }}">
                <input type="text" class="form-control" value="{{ $mahasiswa->user->nim ?? '-' }} - {{ $mahasiswa->nama }}" disabled>
            </div>

            <div class="form-group mb-3">
                <label for="mata_kuliah_id" class="form-label">Mata Kuliah</label>
                <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-control @error('mata_kuliah_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Mata Kuliah --</option>
                    @foreach($mataKuliahs as $mk)
                        <option value="{{ $mk->id }}" {{ old('mata_kuliah_id') == $mk->id ? 'selected' : '' }}>
                            {{ $mk->kode_mk }} - {{ $mk->nama_mk }} ({{ $mk->sks }} SKS)
                        </option>
                    @endforeach
                </select>
                @error('mata_kuliah_id') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-3 gap-3 mb-4">
                <div class="form-group mb-0">
                    <label for="hari" class="form-label">Hari</label>
                    <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror" required>
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                            <option value="{{ $h }}" {{ old('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                    @error('hari') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai') }}" required>
                    @error('jam_mulai') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai') }}" required>
                    @error('jam_selesai') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-2 gap-3 mb-4">
                <div class="form-group mb-0">
                    <label for="ruangan_id" class="form-label">Ruangan</label>
                    <select name="ruangan_id" id="ruangan_id" class="form-control @error('ruangan_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ old('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->nama_ruangan }}
                            </option>
                        @endforeach
                    </select>
                    @error('ruangan_id') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="dosen_id" class="form-label">Dosen Pengampu</label>
                    <select name="dosen_id" id="dosen_id" class="form-control @error('dosen_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->nama_dosen }}
                            </option>
                        @endforeach
                    </select>
                    @error('dosen_id') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                <a href="{{ sim_route('admin.jadwal-kuliahs.index', ['mahasiswa_id' => $mahasiswa->id]) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
