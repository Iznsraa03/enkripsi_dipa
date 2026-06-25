@extends('layouts.app')

@section('title', 'Edit Jadwal Kuliah')
@section('page_title', 'Edit Jadwal Kuliah')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="card-header-title">
            <span class="material-symbols-outlined fill">edit_calendar</span>
            Edit Jadwal: {{ $jadwalKuliah->mataKuliah->nama_mk }}
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.jadwal-kuliahs.update', $jadwalKuliah) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="mata_kuliah_id" class="form-label">Mata Kuliah</label>
                <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-control @error('mata_kuliah_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Mata Kuliah --</option>
                    @foreach($mataKuliahs as $mk)
                        <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $jadwalKuliah->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>
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
                            <option value="{{ $h }}" {{ old('hari', $jadwalKuliah->hari) == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                    @error('hari') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai', date('H:i', strtotime($jadwalKuliah->jam_mulai))) }}" required>
                    @error('jam_mulai') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai', date('H:i', strtotime($jadwalKuliah->jam_selesai))) }}" required>
                    @error('jam_selesai') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-2 gap-3 mb-4">
                <div class="form-group mb-0">
                    <label for="ruangan" class="form-label">Ruangan</label>
                    <input type="text" name="ruangan" id="ruangan" class="form-control @error('ruangan') is-invalid @enderror" value="{{ old('ruangan', $jadwalKuliah->ruangan) }}" required>
                    @error('ruangan') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="dosen" class="form-label">Dosen Pengampu</label>
                    <input type="text" name="dosen" id="dosen" class="form-control @error('dosen') is-invalid @enderror" value="{{ old('dosen', $jadwalKuliah->dosen) }}" required>
                    @error('dosen') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.jadwal-kuliahs.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
