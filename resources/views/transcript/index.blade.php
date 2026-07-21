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
        <div class="gpa-card-value">{{ $ipk }}</div>
        <div class="gpa-card-label">IPK Saat Ini</div>
    </div>

    <div class="gpa-card">
        <div class="gpa-card-icon green">
            <span class="material-symbols-outlined fill">calculate</span>
        </div>
        <div class="gpa-card-value">{{ $totalSks }}</div>
        <div class="gpa-card-label">Total SKS</div>
    </div>

    <div class="gpa-card">
        <div class="gpa-card-icon blue">
            <span class="material-symbols-outlined fill">check_circle</span>
        </div>
        <div class="gpa-card-value">{{ collect($nilais)->whereIn('grade', ['A','A-','B+','B','B-','C+','C'])->count() }}</div>
        <div class="gpa-card-label">Mata Kuliah Lulus</div>
    </div>

    <div class="gpa-card">
        <div class="gpa-card-icon purple">
            <span class="material-symbols-outlined fill">workspace_premium</span>
        </div>
        <div class="gpa-card-value" style="font-size:16px;">{{ (float)$ipk >= 3.5 ? 'Cum Laude' : 'Sangat Memuaskan' }}</div>
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
            <span style="font-size:12px;color:var(--color-text-muted);">{{ $mahasiswa->nama ?? 'Nama Mahasiswa' }} · NIM {{ auth()->user()->nim }}</span>
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
                    @forelse($nilais as $nilai)
                    <tr>
                        <td class="text-mono" style="font-size:12px;">{{ $nilai->mataKuliah->kode_mk }}</td>
                        <td>{{ $nilai->mataKuliah->nama_mk }}</td>
                        <td style="text-align:center;">{{ $nilai->mataKuliah->sks }}</td>
                        <td style="text-align:center;" class="grade-{{ strtolower(substr($nilai->grade, 0, 1)) }}">{{ $nilai->grade }}</td>
                        <td style="text-align:center;">{{ is_numeric($nilai->nilai_angka) ? number_format((float)$nilai->nilai_angka, 1) : '-' }}</td>
                        <td><span class="badge badge-primary">{{ $nilai->semester_tahun }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada transkrip nilai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex justify-between align-center">
        <span style="font-size:12px;color:var(--color-text-muted);">
            Total: <strong>{{ collect($nilais)->count() }}</strong> mata kuliah · <strong>{{ $totalSks }}</strong> SKS ditampilkan
        </span>
        <span class="security-badge {{ config('app.simulation') ? '' : 'active' }}" style="font-size:11px; {{ config('app.simulation') ? 'background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);' : '' }}">
            <span class="dot" style="{{ config('app.simulation') ? 'background: #ef4444;' : '' }}"></span>
            {{ config('app.simulation') ? 'Unencrypted (Plaintext)' : 'AES-256-GCM Protected' }}
        </span>
    </div>
</div>

{{-- Right: Verification Card --}}
<div style="display:flex; flex-direction:column; gap:16px;">

    {{-- Transcript Security Verification --}}
    <div class="verification-card" style="{{ config('app.simulation') ? 'border: 1px solid rgba(239, 68, 68, 0.2); background: rgba(239, 68, 68, 0.05);' : '' }}">
        <div class="verification-card-title" style="{{ config('app.simulation') ? 'color: #ef4444;' : '' }}">
            <span class="material-symbols-outlined" style="font-size:16px; vertical-align:middle; margin-right:4px;">{{ config('app.simulation') ? 'warning' : 'verified_user' }}</span>
            {{ config('app.simulation') ? 'Simulation Verification' : 'Transcript Security Verification' }}
        </div>
        <div class="verification-card-sub">{{ config('app.simulation') ? 'Mode simulasi aktif. Sistem tidak menggunakan enkripsi AES-256-GCM dan verifikasi menggunakan hash Bcrypt.' : 'Verifikasi diperlukan sebelum mengunduh transkrip resmi.' }}</div>

        <div class="verification-badge-list">
            <div class="verification-badge-item">
                <span class="dot" style="{{ config('app.simulation') ? 'background: #ef4444;' : '' }}"></span>
                {{ config('app.simulation') ? 'PIN Verification (Bypass)' : 'PIN Verification Required' }}
            </div>
            <div class="verification-badge-item">
                <span class="dot" style="{{ config('app.simulation') ? 'background: #ef4444;' : '' }}"></span>
                {{ config('app.simulation') ? 'Hashed using Bcrypt' : 'Protected using Argon2id' }}
            </div>
            <div class="verification-badge-item">
                <span class="dot" style="{{ config('app.simulation') ? 'background: #ef4444;' : '' }}"></span>
                {{ config('app.simulation') ? 'Unsecure Download' : 'Secure Download Available' }}
            </div>
            <div class="verification-badge-item">
                <span class="dot" style="{{ config('app.simulation') ? 'background: #ef4444;' : '' }}"></span>
                {{ config('app.simulation') ? 'Plaintext Export' : 'AES-256-GCM Encrypted' }}
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
            <div id="pinErrorMsg" style="display:none;font-size:11px;color:#f53003;margin-top:6px;font-weight:600;"></div>
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
                    <div style="font-size:44px;font-weight:800;color:var(--color-primary-deepdark);line-height:1;">{{ $ipk }}</div>
                    <div style="font-size:12px;color:var(--color-success);font-weight:600;margin-top:4px;">★ {{ (float)$ipk >= 3.5 ? 'Cum Laude' : 'Sangat Memuaskan' }}</div>
                    <div class="divider"></div>
                    <div style="font-size:12px;color:var(--color-text-muted);">{{ $totalSks }} SKS · {{ collect($nilais)->count() }} Mata Kuliah</div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    let isPinVerified = false;

    document.getElementById('verifyPinBtn').addEventListener('click', function () {
        const pinInput = document.getElementById('verificationPin');
        const pin = pinInput.value;
        const errorMsg = document.getElementById('pinErrorMsg');
        const btn = this;

        if (pin.length < 4) {
            errorMsg.innerText = 'Masukkan PIN verifikasi terlebih dahulu (min. 4 digit).';
            errorMsg.style.display = 'block';
            return;
        }

        errorMsg.style.display = 'none';
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="margin-right: 6px; width: 12px; height: 12px; display: inline-block;"></span> Verifying...';

        fetch('{{ sim_route("transcript.verify-pin") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ pin: pin })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            btn.disabled = false;
            if (status === 200 && body.success) {
                isPinVerified = true;
                btn.innerHTML = '<span class="material-symbols-outlined">check_circle</span> PIN Verified!';
                btn.style.background = '#198754'; // success green
                btn.style.borderColor = '#198754';
                btn.style.color = '#fff';
                btn.disabled = true;
                pinInput.disabled = true;

                // Update download button state
                const downloadBtn = document.getElementById('downloadTranscriptBtn');
                downloadBtn.classList.remove('btn-secondary');
                downloadBtn.classList.add('btn-primary');
                downloadBtn.style.background = 'var(--color-primary)';
                downloadBtn.style.color = '#1a1a1a';
                downloadBtn.style.borderColor = 'var(--color-primary-dark)';
            } else {
                errorMsg.innerText = body.message || 'Verifikasi PIN gagal.';
                errorMsg.style.display = 'block';
                btn.innerHTML = '<span class="material-symbols-outlined">lock_open</span> Verify PIN';
            }
        })
        .catch(err => {
            btn.disabled = false;
            errorMsg.innerText = 'Terjadi kesalahan sistem. Silakan coba lagi.';
            errorMsg.style.display = 'block';
            btn.innerHTML = '<span class="material-symbols-outlined">lock_open</span> Verify PIN';
            console.error(err);
        });
    });

    document.getElementById('downloadTranscriptBtn').addEventListener('click', function () {
        if (!isPinVerified) {
            alert('Fitur download akan tersedia setelah verifikasi PIN berhasil.');
            return;
        }

        // Redirect to download route
        window.location.href = '{{ sim_route("transcript.download") }}';

        // Reset verification state after starting download for security
        setTimeout(() => {
            isPinVerified = false;
            const btn = document.getElementById('verifyPinBtn');
            const pinInput = document.getElementById('verificationPin');
            const downloadBtn = document.getElementById('downloadTranscriptBtn');

            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined">lock_open</span> Verify PIN';
            btn.style.background = '';
            btn.style.borderColor = '';
            btn.style.color = '';

            pinInput.disabled = false;
            pinInput.value = '';

            downloadBtn.classList.remove('btn-primary');
            downloadBtn.classList.add('btn-secondary');
            downloadBtn.style.background = 'rgba(255,255,255,0.1)';
            downloadBtn.style.color = '#fff';
            downloadBtn.style.borderColor = 'rgba(255,255,255,0.2)';
        }, 1000);
    });
</script>
@endpush
