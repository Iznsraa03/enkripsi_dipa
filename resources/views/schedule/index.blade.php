@extends('layouts.app')

@section('title', 'Jadwal Kuliah')
@section('page_title', 'Jadwal Kuliah')

@section('content')

{{-- Security Info Alert --}}
<div class="alert alert-security mb-4">
    <span class="material-symbols-outlined">encrypted</span>
    <div>
        <strong style="font-size:13px;">Data Jadwal Terenkripsi</strong>
        <div style="font-size:12px; margin-top:2px;">
            Seluruh catatan jadwal kuliah dilindungi menggunakan enkripsi <strong>AES-256-GCM</strong> sebelum disimpan ke dalam database. Data hanya dapat dibaca oleh pengguna yang terautentikasi.
        </div>
    </div>
</div>

{{-- Schedule Card --}}
<div class="card">
    <div class="card-header">
        <div class="card-header-title">
            <span class="material-symbols-outlined fill">calendar_month</span>
            Jadwal Kuliah — Semester 8
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
                    <tr>
                        <td class="text-mono" style="font-size:12px;">SI-401</td>
                        <td style="font-weight:600;">Keamanan Informasi</td>
                        <td>Senin</td>
                        <td>08:00 – 10:00</td>
                        <td>Lab A201</td>
                        <td>Dr. Ahmad Fauzi, M.Kom</td>
                        <td class="text-center">3</td>
                        <td><span class="badge badge-success"><span class="material-symbols-outlined fill" style="font-size:11px; margin-right:4px;">check_circle</span>Aktif</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">SI-402</td>
                        <td style="font-weight:600;">Kriptografi Terapan</td>
                        <td>Selasa</td>
                        <td>10:00 – 12:00</td>
                        <td>R. 305</td>
                        <td>Dr. Sari Dewi, M.T</td>
                        <td class="text-center">3</td>
                        <td><span class="badge badge-success"><span class="material-symbols-outlined fill" style="font-size:11px; margin-right:4px;">check_circle</span>Aktif</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">SI-403</td>
                        <td style="font-weight:600;">Pengembangan Sistem Web</td>
                        <td>Rabu</td>
                        <td>13:00 – 15:00</td>
                        <td>Lab B102</td>
                        <td>Budi Santoso, S.Kom, M.Cs</td>
                        <td class="text-center">4</td>
                        <td><span class="badge badge-success"><span class="material-symbols-outlined fill" style="font-size:11px; margin-right:4px;">check_circle</span>Aktif</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">SI-404</td>
                        <td style="font-weight:600;">Metodologi Penelitian</td>
                        <td>Kamis</td>
                        <td>08:00 – 10:00</td>
                        <td>R. 210</td>
                        <td>Prof. Rahmawati, Ph.D</td>
                        <td class="text-center">2</td>
                        <td><span class="badge badge-success"><span class="material-symbols-outlined fill" style="font-size:11px; margin-right:4px;">check_circle</span>Aktif</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">SI-405</td>
                        <td style="font-weight:600;">Basis Data Lanjut</td>
                        <td>Kamis</td>
                        <td>13:00 – 15:00</td>
                        <td>Lab C203</td>
                        <td>Eko Prasetyo, M.Kom</td>
                        <td class="text-center">3</td>
                        <td><span class="badge badge-success"><span class="material-symbols-outlined fill" style="font-size:11px; margin-right:4px;">check_circle</span>Aktif</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">SI-406</td>
                        <td style="font-weight:600;">Tugas Akhir / Skripsi</td>
                        <td>Jumat</td>
                        <td>09:00 – 11:00</td>
                        <td>R. Pembimbing</td>
                        <td>Dr. Ahmad Fauzi, M.Kom</td>
                        <td class="text-center">6</td>
                        <td><span class="badge badge-warning"><span class="material-symbols-outlined" style="font-size:11px; margin-right:4px;">pending</span>Bimbingan</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">SI-407</td>
                        <td style="font-weight:600;">Etika Profesi IT</td>
                        <td>Jumat</td>
                        <td>13:00 – 15:00</td>
                        <td>R. 401</td>
                        <td>Indra Kusuma, S.H, M.H</td>
                        <td class="text-center">2</td>
                        <td><span class="badge badge-success"><span class="material-symbols-outlined fill" style="font-size:11px; margin-right:4px;">check_circle</span>Aktif</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Footer --}}
    <div class="card-footer d-flex justify-between align-center">
        <span style="font-size:12px; color:var(--color-text-muted);">
            <span class="material-symbols-outlined" style="font-size:14px; vertical-align:middle;">info</span>
            Menampilkan <strong>7</strong> mata kuliah · Total SKS: <strong>23</strong>
        </span>
        <span class="security-badge active" style="font-size:11px;">
            <span class="dot"></span>
            AES-256-GCM Protected
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
