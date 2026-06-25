<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Akademik yang dilindungi dengan enkripsi Argon2id dan AES-256-GCM untuk keamanan data mahasiswa.">
    <title>@yield('title', 'Dashboard') — Secure SIAKAD</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

    @stack('styles')
</head>
<body>

<div class="app-wrapper">

    {{-- Sidebar Overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Main Area --}}
    <div class="main-content" id="mainContent">

        {{-- Navbar --}}
        @include('components.navbar')

        {{-- Page Header Banner --}}
        <div class="page-header-banner">
            <h2 class="page-header-title">@yield('page_title', 'Dashboard')</h2>
            <nav class="page-header-breadcrumb">
                <span class="material-symbols-outlined">home</span>
                <span>/</span>
                <span>@yield('page_title', 'Dashboard')</span>
            </nav>
        </div>

        {{-- Page Content --}}
        <div class="page-canvas">
            <div class="page-container">
                @yield('content')
            </div>
        </div>

    </div>{{-- end .main-content --}}
</div>{{-- end .app-wrapper --}}

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Sidebar toggle
    function openSidebar() {
        document.getElementById('appSidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        document.getElementById('appSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Highlight active sidebar item
    document.addEventListener('DOMContentLoaded', function () {
        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar-nav-item').forEach(function (link) {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>
