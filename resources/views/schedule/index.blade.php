@extends('layouts.app')

@section('title', 'Jadwal Kuliah')
@section('page_title', 'Jadwal Kuliah')

@section('content')

{{-- Security Info Alert --}}
<div class="alert alert-security mb-4">
    <span class="material-symbols-outlined">encrypted</span>
    <div>
        <strong style="font-size:13px;">{{ config('app.simulation') ? 'Data Jadwal (Plaintext)' : 'Data Jadwal Terenkripsi' }}</strong>
        <div style="font-size:12px; margin-top:2px;">
            {{ config('app.simulation') ? 'Seluruh catatan jadwal kuliah disimpan dalam format plaintext (tanpa enkripsi) pada tabel terpisah.' : 'Seluruh catatan jadwal kuliah dilindungi menggunakan enkripsi <strong>AES-256-GCM</strong> sebelum disimpan ke dalam database. Data hanya dapat dibaca oleh pengguna yang terautentikasi.' }}
        </div>
    </div>
</div>

{{-- Schedule Card --}}
<div class="card">
    <div class="card-header">
        <div class="card-header-title">
            <span class="material-symbols-outlined fill">calendar_month</span>
            Jadwal Kuliah — Semester {{ $mahasiswa->semester ?? '-' }}
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="card-body" style="padding-bottom: 0;">
        <div class="toolbar mb-3">
            {{-- Search --}}
            <div class="search-bar flex-1">
                <div class="search-input-wrapper" style="max-width:340px;">
                    <span class="material-symbols-outlined">search</span>
                    <input
                        type="text"
                        id="scheduleSearch"
                        placeholder="Cari mata kuliah, dosen, ruangan..."
                        oninput="filterTable()"
                    >
                </div>
            </div>

            {{-- Semester Filter --}}
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <label style="font-size:12px; font-weight:600; color:var(--color-text-secondary); white-space:nowrap;">Semester:</label>
                <select class="filter-select" id="semesterFilter" onchange="filterTable()">
                    <option value="">Semua Semester</option>
                    <option value="8" selected>Semester 8</option>
                    <option value="7">Semester 7</option>
                    <option value="6">Semester 6</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" id="scheduleTable">
                <thead>
                    <tr>
                        <th>
                            <span class="th-wrap">
                                <span class="material-symbols-outlined">code</span>
                                Kode MK
                            </span>
                        </th>
                        <th>
                            <span class="th-wrap">
                                <span class="material-symbols-outlined">book</span>
                                Mata Kuliah
                            </span>
                        </th>
                        <th>
                            <span class="th-wrap">
                                <span class="material-symbols-outlined">today</span>
                                Hari
                            </span>
                        </th>
                        <th>
                            <span class="th-wrap">
                                <span class="material-symbols-outlined">schedule</span>
                                Waktu
                            </span>
                        </th>
                        <th>
                            <span class="th-wrap">
                                <span class="material-symbols-outlined">meeting_room</span>
                                Ruangan
                            </span>
                        </th>
                        <th>
                            <span class="th-wrap">
                                <span class="material-symbols-outlined">co_present</span>
                                Dosen Pengampu
                            </span>
                        </th>
                        <th style="text-align:center;">
                            <span class="th-wrap" style="justify-content:center; width:100%;">
                                <span class="material-symbols-outlined">grade</span>
                                SKS
                            </span>
                        </th>
                        <th>
                            <span class="th-wrap">
                                <span class="material-symbols-outlined">info</span>
                                Status
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody id="scheduleBody">
                    @forelse($jadwals as $jadwal)
                    <tr>
                        <td class="text-mono" style="font-size:12px;">{{ $jadwal->mataKuliah->kode_mk }}</td>
                        <td style="font-weight:600;">{{ $jadwal->mataKuliah->nama_mk }}</td>
                        <td>{{ $jadwal->hari }}</td>
                        <td>{{ date('H:i', strtotime($jadwal->jam_mulai)) }} – {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</td>
                        <td>{{ $jadwal->ruangan->nama_ruangan ?? '-' }}</td>
                        <td>{{ $jadwal->dosen->nama_dosen ?? '-' }}</td>
                        <td class="text-center">{{ $jadwal->mataKuliah->sks }}</td>
                        <td><span class="badge badge-success"><span class="material-symbols-outlined fill" style="font-size:11px; margin-right:4px;">check_circle</span>Aktif</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada jadwal kuliah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Footer --}}
    <div class="card-footer d-flex justify-between align-center">
        <span style="font-size:12px; color:var(--color-text-muted);">
            <span class="material-symbols-outlined" style="font-size:14px; vertical-align:middle;">info</span>
            Menampilkan <strong>{{ $jadwals->count() }}</strong> mata kuliah · Total SKS: <strong>{{ $jadwals->sum(fn($j) => $j->mataKuliah->sks) }}</strong>
        </span>
        <span class="security-badge {{ config('app.simulation') ? '' : 'active' }}" style="font-size:11px; {{ config('app.simulation') ? 'background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);' : '' }}">
            <span class="dot" style="{{ config('app.simulation') ? 'background: #ef4444;' : '' }}"></span>
            {{ config('app.simulation') ? 'Unencrypted (Plaintext)' : 'AES-256-GCM Protected' }}
        </span>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function filterTable() {
        const searchVal = document.getElementById('scheduleSearch').value.toLowerCase();
        const rows = document.querySelectorAll('#scheduleBody tr');

        rows.forEach(function (row) {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchVal) ? '' : 'none';
        });
    }
</script>
@endpush
