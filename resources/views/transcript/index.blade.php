@extends('layouts.app')

@section('title', 'Transkrip Nilai')
@section('page_title', 'Transkrip Nilai')

@section('content')

{{-- GPA Summary Cards --}}
<div class="gpa-cards">

    <div class="gpa-card">
        <div class="gpa-card-icon gold">
            <span class="material-symbols-outlined fill">star</span>
        </div>
        <div class="gpa-card-value">3.92</div>
        <div class="gpa-card-label">IPK Saat Ini</div>
    </div>

    <div class="gpa-card">
        <div class="gpa-card-icon green">
            <span class="material-symbols-outlined fill">calculate</span>
        </div>
        <div class="gpa-card-value">144</div>
        <div class="gpa-card-label">Total SKS</div>
    </div>

    <div class="gpa-card">
        <div class="gpa-card-icon blue">
            <span class="material-symbols-outlined fill">check_circle</span>
        </div>
        <div class="gpa-card-value">48</div>
        <div class="gpa-card-label">Mata Kuliah Lulus</div>
    </div>

    <div class="gpa-card">
        <div class="gpa-card-icon purple">
            <span class="material-symbols-outlined fill">workspace_premium</span>
        </div>
        <div class="gpa-card-value" style="font-size:16px;">Cum Laude</div>
        <div class="gpa-card-label">Status Akademik</div>
    </div>

</div>

{{-- Two-column: Transcript Table + Verification Card --}}
<div class="grid" style="grid-template-columns: 1fr 300px; gap:20px;">

    {{-- Left: Transcript Table --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">
                <span class="material-symbols-outlined fill">description</span>
                Transkrip Nilai Akademik
            </div>
            <span style="font-size:12px;color:var(--color-text-muted);">Najwa Rizqiyah Munir · NIM 221043</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="transcriptTable">
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
                        <th style="text-align:center;">
                            <span class="th-wrap" style="justify-content:center; width:100%;">
                                <span class="material-symbols-outlined">grade</span>
                                SKS
                            </span>
                        </th>
                        <th style="text-align:center;">
                            <span class="th-wrap" style="justify-content:center; width:100%;">
                                <span class="material-symbols-outlined">military_tech</span>
                                Nilai
                            </span>
                        </th>
                        <th style="text-align:center;">
                            <span class="th-wrap" style="justify-content:center; width:100%;">
                                <span class="material-symbols-outlined">functions</span>
                                Bobot
                            </span>
                        </th>
                        <th>
                            <span class="th-wrap">
                                <span class="material-symbols-outlined">school</span>
                                Semester
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">MTK-101</td>
                        <td>Matematika Diskrit</td>
                        <td style="text-align:center;">3</td>
                        <td style="text-align:center;" class="grade-a">A</td>
                        <td style="text-align:center;">4.0</td>
                        <td><span class="badge badge-primary">Sem. 1</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">SI-101</td>
                        <td>Pengantar Sistem Informasi</td>
                        <td style="text-align:center;">3</td>
                        <td style="text-align:center;" class="grade-a">A</td>
                        <td style="text-align:center;">4.0</td>
                        <td><span class="badge badge-primary">Sem. 1</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">PRG-101</td>
                        <td>Pemrograman Dasar</td>
                        <td style="text-align:center;">4</td>
                        <td style="text-align:center;" class="grade-a">A</td>
                        <td style="text-align:center;">4.0</td>
                        <td><span class="badge badge-primary">Sem. 1</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">BD-201</td>
                        <td>Basis Data</td>
                        <td style="text-align:center;">3</td>
                        <td style="text-align:center;" class="grade-b">B+</td>
                        <td style="text-align:center;">3.5</td>
                        <td><span class="badge badge-info">Sem. 2</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">NET-201</td>
                        <td>Jaringan Komputer</td>
                        <td style="text-align:center;">3</td>
                        <td style="text-align:center;" class="grade-a">A</td>
                        <td style="text-align:center;">4.0</td>
                        <td><span class="badge badge-info">Sem. 2</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">WEB-301</td>
                        <td>Pemrograman Web</td>
                        <td style="text-align:center;">4</td>
                        <td style="text-align:center;" class="grade-a">A</td>
                        <td style="text-align:center;">4.0</td>
                        <td><span class="badge badge-warning">Sem. 3</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">OS-301</td>
                        <td>Sistem Operasi</td>
                        <td style="text-align:center;">3</td>
                        <td style="text-align:center;" class="grade-b">B+</td>
                        <td style="text-align:center;">3.5</td>
                        <td><span class="badge badge-warning">Sem. 3</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">RPL-401</td>
                        <td>Rekayasa Perangkat Lunak</td>
                        <td style="text-align:center;">4</td>
                        <td style="text-align:center;" class="grade-a">A</td>
                        <td style="text-align:center;">4.0</td>
                        <td><span class="badge badge-success">Sem. 4</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">AI-501</td>
                        <td>Kecerdasan Buatan</td>
                        <td style="text-align:center;">3</td>
                        <td style="text-align:center;" class="grade-a">A</td>
                        <td style="text-align:center;">4.0</td>
                        <td><span class="badge badge-success">Sem. 5</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">SEC-601</td>
                        <td>Keamanan Jaringan</td>
                        <td style="text-align:center;">3</td>
                        <td style="text-align:center;" class="grade-a">A</td>
                        <td style="text-align:center;">4.0</td>
                        <td><span class="badge badge-success">Sem. 6</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">CRYPTO-701</td>
                        <td>Kriptografi</td>
                        <td style="text-align:center;">3</td>
                        <td style="text-align:center;" class="grade-a">A</td>
                        <td style="text-align:center;">4.0</td>
                        <td><span class="badge badge-success">Sem. 7</span></td>
                    </tr>
                    <tr>
                        <td class="text-mono" style="font-size:12px;">TA-801</td>
                        <td>Tugas Akhir / Skripsi</td>
                        <td style="text-align:center;">6</td>
                        <td style="text-align:center;" class="text-muted">—</td>
                        <td style="text-align:center;" class="text-muted">—</td>
                        <td><span class="badge badge-warning">Sem. 8</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex justify-between align-center">
        <span style="font-size:12px;color:var(--color-text-muted);">
            Total: <strong>12</strong> mata kuliah · <strong>42</strong> SKS ditampilkan
        </span>
        <span class="security-badge active" style="font-size:11px;">
            <span class="dot"></span>
            AES-256-GCM Protected
        </span>
    </div>
</div>

{{-- Right: Verification Card --}}
<div style="display:flex; flex-direction:column; gap:16px;">

    {{-- Transcript Security Verification --}}
    <div class="verification-card">
        <div class="verification-card-title">
            <span class="material-symbols-outlined" style="font-size:16px; vertical-align:middle; margin-right:4px;">verified_user</span>
            Transcript Security Verification
        </div>
        <div class="verification-card-sub">Verifikasi diperlukan sebelum mengunduh transkrip resmi.</div>

        <div class="verification-badge-list">
            <div class="verification-badge-item">
                <span class="dot"></span>
                PIN Verification Required
            </div>
            <div class="verification-badge-item">
                <span class="dot"></span>
                Protected using Argon2id
            </div>
            <div class="verification-badge-item">
                <span class="dot"></span>
                Secure Download Available
            </div>
            <div class="verification-badge-item">
                <span class="dot"></span>
                AES-256-GCM Encrypted
            </div>
        </div>

        {{-- PIN Input --}}
        <div class="form-group mb-3">
            <label style="display:block;font-size:11px;font-weight:600;color:rgba(255,255,255,0.5);margin-bottom:6px;letter-spacing:0.05em;text-transform:uppercase;">Masukkan PIN Verifikasi</label>
            <div class="input-group">
                <input
                    type="password"
                    id="verificationPin"
                    placeholder="••••••"
                    maxlength="6"
                    style="width:100%;padding:9px 40px 9px 12px;border:1px solid rgba(255,255,255,0.15);border-radius:var(--radius-sm);background:rgba(255,255,255,0.07);color:#fff;font-size:14px;font-family:var(--font-mono);letter-spacing:0.1em;outline:none;"
                >
                <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);">
                    <span class="material-symbols-outlined" style="font-size:16px;color:rgba(255,255,255,0.4);">key</span>
                </span>
            </div>
        </div>

        {{-- Buttons --}}
        <div style="display:flex;flex-direction:column;gap:8px;">
            <button class="btn btn-primary w-100" id="verifyPinBtn" style="justify-content:center;background:var(--color-primary);color:#1a1a1a;border-color:var(--color-primary-dark);">
                <span class="material-symbols-outlined">lock_open</span>
                Verify PIN
            </button>
            <button class="btn btn-secondary w-100" id="downloadTranscriptBtn" style="justify-content:center;background:rgba(255,255,255,0.1);color:#fff;border-color:rgba(255,255,255,0.2);">
                <span class="material-symbols-outlined">download_for_offline</span>
                Download Transcript
            </button>
        </div>

    </div>

        {{-- IPK Summary --}}
        <div class="card">
            <div class="card-body">
                <div style="text-align:center;padding:8px 0;">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;color:var(--color-text-muted);margin-bottom:6px;">Indeks Prestasi Kumulatif</div>
                    <div style="font-size:44px;font-weight:800;color:var(--color-primary-deepdark);line-height:1;">3.92</div>
                    <div style="font-size:12px;color:var(--color-success);font-weight:600;margin-top:4px;">★ Cum Laude</div>
                    <div class="divider"></div>
                    <div style="font-size:12px;color:var(--color-text-muted);">144 SKS · 48 Mata Kuliah</div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    document.getElementById('verifyPinBtn').addEventListener('click', function () {
        const pin = document.getElementById('verificationPin').value;
        if (pin.length < 4) {
            alert('Masukkan PIN verifikasi terlebih dahulu (min. 4 digit).');
            return;
        }
        // Dummy: show success
        this.innerHTML = '<span class="material-symbols-outlined">check_circle</span> PIN Verified!';
        this.style.background = 'var(--color-success)';
        setTimeout(() => {
            this.innerHTML = '<span class="material-symbols-outlined">lock_open</span> Verify PIN';
            this.style.background = '';
        }, 2500);
    });

    document.getElementById('downloadTranscriptBtn').addEventListener('click', function () {
        alert('Fitur download akan tersedia setelah verifikasi PIN berhasil.');
    });
</script>
@endpush
