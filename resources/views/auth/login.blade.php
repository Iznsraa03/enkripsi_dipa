<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login ke Sistem Informasi Akademik yang dilindungi dengan enkripsi Argon2id dan AES-256-GCM.">
    <title>Login — Secure SIAKAD</title>

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

    <style>
        body { background-color: #e9ecef; }
    </style>
</head>
<body>

<main class="login-page" id="login-page">

    <div class="login-wrapper">

        {{-- Brand Row --}}
        <div class="login-brand">
            {{-- Logo + Name --}}
            <div class="login-logo-wrap">
                <div class="login-logo">
                    <span class="material-symbols-outlined fill">school</span>
                </div>
                <div>
                    <div class="login-brand-name">Secure-SIAKAD</div>
                    <div style="font-size:11px; color:#888;">Sistem Informasi Akademik</div>
                </div>
            </div>

            {{-- SIGN IN Tab --}}
            <div class="login-sign-tab">
                <span class="material-symbols-outlined" style="font-size:18px;">person</span>
                SIGN IN
            </div>
        </div>

        {{-- Login Card --}}
        <div class="login-card">

            {{-- Encryption Notice --}}
            <div class="login-enc-notice">
                <span class="material-symbols-outlined">lock</span>
                <span>Protected with Argon2id &amp; AES-256-GCM Encryption</span>
            </div>

            {{-- Flash Messages --}}
            @if (session('error'))
                <div class="alert alert-danger mb-3" style="font-size:13px; padding:10px 14px; border-radius:8px;">
                    <span class="material-symbols-outlined" style="font-size:16px; vertical-align:middle;">error</span>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Login Form --}}
            <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                @csrf

                {{-- NIM Field --}}
                <div class="form-group">
                    <label class="form-label" for="nim">NIM / NIDN / USERNAME</label>
                    <div class="input-group">
                        <input
                            type="text"
                            id="nim"
                            name="nim"
                            class="form-control has-icon {{ $errors->has('nim') ? 'is-invalid' : '' }}"
                            placeholder="Masukkan NIM Anda"
                            autocomplete="username"
                            value="{{ old('nim') }}"
                        >
                        <span class="input-icon">
                            <span class="material-symbols-outlined">account_circle</span>
                        </span>
                    </div>
                    @error('nim')
                        <div style="color:#dc3545; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control has-icon"
                            placeholder="••••••••"
                            autocomplete="current-password"
                        >
                        <span class="input-icon clickable" id="togglePasswordBtn" role="button" aria-label="Toggle Password">
                            <span class="material-symbols-outlined" id="passwordIcon">visibility</span>
                        </span>
                    </div>
                </div>

                {{-- Submit --}}
                <div style="display:flex; justify-content:flex-end; margin-top:20px;">
                    <button type="submit" class="btn btn-primary" id="signInBtn">
                        <span class="material-symbols-outlined">login</span>
                        Sign In
                    </button>
                </div>
            </form>

            {{-- Divider --}}
            <div class="divider"></div>

            {{-- Lost Password --}}
            <div class="text-center">
                <a href="#" style="color:var(--color-primary-dark); font-size:13px; font-weight:600;">Lost Password?</a>
            </div>

        </div>{{-- end .login-card --}}

        {{-- Footer --}}
        <div class="login-footer">
            <p>&copy; Copyright {{ date('Y') }}. All rights reserved.</p>

            <div class="encryption-badge" style="font-size:11px;">
                <span class="dot"></span>
                <span>ENCRYPTION ACTIVE</span>
            </div>
        </div>

    </div>{{-- end .login-wrapper --}}

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleBtn  = document.getElementById('togglePasswordBtn');
    const passwordEl = document.getElementById('password');
    const passwordIcon = document.getElementById('passwordIcon');

    toggleBtn.addEventListener('click', function () {
        const isPassword = passwordEl.getAttribute('type') === 'password';
        passwordEl.setAttribute('type', isPassword ? 'text' : 'password');
        passwordIcon.textContent = isPassword ? 'visibility_off' : 'visibility';
    });
</script>
</body>
</html>
