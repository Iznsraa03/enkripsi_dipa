@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner mb-4">
    <div class="welcome-title">
        Selamat Datang, <span>{{ $mahasiswa?->nama ?? Auth::user()->nim }}</span> 👋
    </div>
    <div class="welcome-sub">Sistem Informasi Akademik — Akses aman dengan enkripsi berlapis</div>
    <div class="welcome-meta">
        <div class="welcome-meta-item">
            <span class="material-symbols-outlined">badge</span>
            <span>NIM: {{ Auth::user()->nim }}</span>
        </div>
        <div class="welcome-meta-item">
            <span class="material-symbols-outlined">school</span>
            <span>{{ $mahasiswa?->program_studi ?? '-' }}</span>
        </div>
        <div class="welcome-meta-item">
            <span class="material-symbols-outlined">calendar_today</span>
            <span>Semester {{ $mahasiswa?->semester ?? '-' }}</span>
        </div>
    </div>
</div>

{{-- Summary Stat Cards --}}
<div class="grid grid-4 mb-4">

    {{-- Card 1: NIM --}}
    <div class="stat-card">
        <div class="stat-card-icon gold">
            <span class="material-symbols-outlined xxl fill">badge</span>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-label">Student ID</div>
            <div class="stat-card-value">{{ Auth::user()->nim }}</div>
            <div class="stat-card-sub">Nomor Induk Mahasiswa</div>
        </div>
    </div>

    {{-- Card 2: Prodi --}}
    <div class="stat-card">
        <div class="stat-card-icon blue">
            <span class="material-symbols-outlined xxl fill">account_balance</span>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-label">Program Studi</div>
            <div class="stat-card-value" style="font-size:16px; font-weight:700;">{{ $mahasiswa?->program_studi ?? '-' }}</div>
            <div class="stat-card-sub">{{ $mahasiswa?->angkatan ? 'Angkatan ' . $mahasiswa->angkatan : 'Fakultas Teknologi' }}</div>
        </div>
    </div>

    {{-- Card 3: Semester --}}
    <div class="stat-card">
        <div class="stat-card-icon purple">
            <span class="material-symbols-outlined xxl fill">calendar_month</span>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-label">Semester</div>
            <div class="stat-card-value">{{ $stats['semester'] }}</div>
            <div class="stat-card-sub">Semester Aktif</div>
        </div>
    </div>

    {{-- Card 4: IPK --}}
    <div class="stat-card">
        <div class="stat-card-icon green">
            <span class="material-symbols-outlined xxl fill">star</span>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-label">IPK</div>
            <div class="stat-card-value">{{ $stats['ipk'] }}</div>
            <div class="stat-card-sub">Indeks Prestasi Kumulatif</div>
        </div>
    </div>

</div>

{{-- Two Column: Profile Card + Security Status --}}
<div class="grid grid-2">

    {{-- Student Profile Card --}}
    <div class="card">
        {{-- Gold Banner --}}
        <div class="profile-banner">
            <div class="profile-seal">
                <span class="material-symbols-outlined fill xxl">school</span>
            </div>
        </div>

        {{-- Profile Details --}}
        <div class="profile-details">
            <h3>Identitas Mahasiswa</h3>

            <div class="profile-row">
                <div class="profile-key">
                    <span class="material-symbols-outlined fill">badge</span>
                    NIM
                </div>
                <div class="profile-value">: {{ Auth::user()->nim }}</div>
            </div>

            <div class="profile-row">
                <div class="profile-key">
                    <span class="material-symbols-outlined fill">person</span>
                    Nama
                </div>
                <div class="profile-value">: {{ $mahasiswa?->nama ?? '-' }}</div>
            </div>

            <div class="profile-row">
                <div class="profile-key">
                    <span class="material-symbols-outlined fill">email</span>
                    Email
                </div>
                <div class="profile-value">: {{ $mahasiswa?->email ?? '-' }}</div>
            </div>

            <div class="profile-row">
                <div class="profile-key">
                    <span class="material-symbols-outlined fill">account_balance</span>
                    Program Studi
                </div>
                <div class="profile-value">: {{ $mahasiswa?->program_studi ?? '-' }}</div>
            </div>

            <div class="profile-row">
                <div class="profile-key">
                    <span class="material-symbols-outlined fill">calendar_month</span>
                    Semester
                </div>
                <div class="profile-value">: {{ $mahasiswa?->semester ?? '-' }}</div>
            </div>

            <div class="profile-row">
                <div class="profile-key">
                    <span class="material-symbols-outlined fill">star</span>
                    IPK
                </div>
                <div class="profile-value" style="font-weight:700; color:var(--color-success);">: {{ $stats['ipk'] }}</div>
            </div>
        </div>
    </div>

    {{-- Security Status Card --}}
    <div class="d-flex" style="flex-direction:column; gap:16px;">

        {{-- Security Status --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-title">
                    <span class="material-symbols-outlined fill">security</span>
                    Status Keamanan Sistem
                </div>
                <span class="security-badge active">
                    <span class="dot"></span>
                    SECURE
                </span>
            </div>
            <div class="card-body">
                <div class="security-status-grid">

                    <div class="security-status-item">
                        <div class="status-icon">
                            <span class="material-symbols-outlined">vpn_key</span>
                        </div>
                        <div>
                            <div class="status-label">Argon2id</div>
                            <div class="status-sub">● Active</div>
                        </div>
                    </div>

                    <div class="security-status-item">
                        <div class="status-icon">
                            <span class="material-symbols-outlined">enhanced_encryption</span>
                        </div>
                        <div>
                            <div class="status-label">AES-256-GCM</div>
                            <div class="status-sub">● Active</div>
                        </div>
                    </div>

                    <div class="security-status-item">
                        <div class="status-icon">
                            <span class="material-symbols-outlined">shield</span>
                        </div>
                        <div>
                            <div class="status-label">Session</div>
                            <div class="status-sub">● Protected</div>
                        </div>
                    </div>

                    <div class="security-status-item">
                        <div class="status-icon">
                            <span class="material-symbols-outlined">storage</span>
                        </div>
                        <div>
                            <div class="status-label">Database</div>
                            <div class="status-sub">● Protected</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Quick Info Card --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-title">
                    <span class="material-symbols-outlined">info</span>
                    Info Semester Aktif
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex align-center gap-3 mb-3">
                    <div style="width:48px;height:48px;border-radius:var(--radius-md);background:var(--color-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <span class="material-symbols-outlined fill" style="color:#fff;">calendar_today</span>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--color-text);">Semester Genap 2025/2026</div>
                        <div style="font-size:12px;color:var(--color-text-muted);">Periode Maret – Agustus 2026</div>
                    </div>
                </div>
                <div class="alert alert-security" style="margin-bottom:0;">
                    <span class="material-symbols-outlined">shield</span>
                    <div>
                        <div style="font-size:12px;font-weight:600;margin-bottom:2px;">Data Terenkripsi</div>
                        <div style="font-size:11px;">Seluruh data akademik Anda disimpan dalam format terenkripsi AES-256-GCM di database.</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
