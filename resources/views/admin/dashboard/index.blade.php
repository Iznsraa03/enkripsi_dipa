@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Admin Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner mb-4">
    <div class="welcome-title">
        Selamat Datang, <span>Administrator</span> 👋
    </div>
    <div class="welcome-sub">Sistem Informasi Akademik — Mode Administrator (Full Access)</div>
</div>

{{-- Summary Stat Cards --}}
<div class="grid grid-4 mb-4">

    {{-- Card 1: Users --}}
    <div class="stat-card">
        <div class="stat-card-icon blue">
            <span class="material-symbols-outlined xxl fill">group</span>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-label">Total Users</div>
            <div class="stat-card-value">{{ $stats['total_users'] }}</div>
            <div class="stat-card-sub">Akun Sistem</div>
        </div>
    </div>

    {{-- Card 2: Mahasiswa --}}
    <div class="stat-card">
        <div class="stat-card-icon green">
            <span class="material-symbols-outlined xxl fill">person</span>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-label">Mahasiswa</div>
            <div class="stat-card-value">{{ $stats['total_mahasiswas'] }}</div>
            <div class="stat-card-sub">Profil Aktif</div>
        </div>
    </div>

    {{-- Card 3: Mata Kuliah --}}
    <div class="stat-card">
        <div class="stat-card-icon gold">
            <span class="material-symbols-outlined xxl fill">book</span>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-label">Mata Kuliah</div>
            <div class="stat-card-value">{{ $stats['total_mata_kuliahs'] }}</div>
            <div class="stat-card-sub">Kurikulum</div>
        </div>
    </div>

    {{-- Card 4: Jadwal --}}
    <div class="stat-card">
        <div class="stat-card-icon purple">
            <span class="material-symbols-outlined xxl fill">calendar_month</span>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-label">Jadwal Kuliah</div>
            <div class="stat-card-value">{{ $stats['total_jadwal'] }}</div>
            <div class="stat-card-sub">Jadwal Aktif</div>
        </div>
    </div>

</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">
                <span class="material-symbols-outlined fill">insights</span>
                Performa Akademik
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex align-center gap-3 mb-3">
                <div style="width:48px;height:48px;border-radius:var(--radius-md);background:var(--color-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span class="material-symbols-outlined fill" style="color:#fff;">star</span>
                </div>
                <div>
                    <div style="font-size:24px;font-weight:700;color:var(--color-text);">{{ $stats['avg_ipk'] }}</div>
                    <div style="font-size:12px;color:var(--color-text-muted);">Rata-rata IPK Global</div>
                </div>
            </div>
            <div style="font-size:13px; color:var(--color-text-secondary); margin-top:16px;">
                Total nilai tercatat: <strong>{{ $stats['total_nilai'] }}</strong>
            </div>
        </div>
    </div>

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
            </div>
        </div>
    </div>
</div>

@endsection
