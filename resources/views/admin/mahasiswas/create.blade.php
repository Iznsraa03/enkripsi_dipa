@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')
@section('page_title', 'Tambah Profil Mahasiswa')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="card-header-title">
            <span class="material-symbols-outlined fill">person_add</span>
            Input Data Mahasiswa Terintegrasi
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-security mb-4">
            <span class="material-symbols-outlined">shield</span>
            <div>
                <div style="font-size:12px;font-weight:600;margin-bottom:2px;">Keamanan Berlapis Otomatis</div>
                <div style="font-size:11px;">Password akan di-hash dengan Argon2id. Data pribadi (Nama, Email, Alamat, No. Telp) akan dienkripsi AES-256-GCM.</div>
            </div>
        </div>

        <form action="{{ sim_route('admin.mahasiswas.store') }}" method="POST">
            @csrf

            <h5 class="mb-3" style="font-size:15px; font-weight:700; color:var(--color-primary-deepdark); border-bottom:1px solid var(--color-border); padding-bottom:8px;">1. Informasi Akun (User)</h5>
            
            <div class="grid grid-2 gap-3 mb-4">
                <div class="form-group mb-0">
                    <label for="nim" class="form-label">NIM (Nomor Induk Mahasiswa)</label>
                    <input type="text" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" required>
                    @error('nim') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="password" class="form-label">Password Sementara</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <h5 class="mb-3" style="font-size:15px; font-weight:700; color:var(--color-primary-deepdark); border-bottom:1px solid var(--color-border); padding-bottom:8px;">2. Informasi Pribadi (Ter-enkripsi)</h5>
            
            <div class="grid grid-2 gap-3 mb-3">
                <div class="form-group mb-0">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                    @error('nama') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="email" class="form-label">Email Aktif</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-2 gap-3 mb-4">
                <div class="form-group mb-0">
                    <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                    <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror" value="{{ old('nomor_telepon') }}">
                    @error('nomor_telepon') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                    <input type="text" name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" value="{{ old('alamat') }}">
                    @error('alamat') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <h5 class="mb-3" style="font-size:15px; font-weight:700; color:var(--color-primary-deepdark); border-bottom:1px solid var(--color-border); padding-bottom:8px;">3. Informasi Akademik</h5>

            <div class="grid grid-3 gap-3 mb-4">
                <div class="form-group mb-0">
                    <label for="jurusan_id" class="form-label">Program Studi</label>
                    <select name="jurusan_id" id="jurusan_id" class="form-control @error('jurusan_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach($jurusans as $jurusan)
                            <option value="{{ $jurusan->id }}" {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                {{ $jurusan->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                    @error('jurusan_id') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="semester" class="form-label">Semester Aktif</label>
                    <input type="number" name="semester" id="semester" class="form-control @error('semester') is-invalid @enderror" value="{{ old('semester', 1) }}" min="1" max="14" required>
                    @error('semester') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-0">
                    <label for="angkatan" class="form-label">Tahun Angkatan</label>
                    <input type="text" name="angkatan" id="angkatan" class="form-control @error('angkatan') is-invalid @enderror" value="{{ old('angkatan', date('Y')) }}" maxlength="4" required>
                    @error('angkatan') <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Mahasiswa</button>
                <a href="{{ sim_route('admin.mahasiswas.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
