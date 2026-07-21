<header class="navbar">

    {{-- Left: Brand --}}
    <div class="navbar-brand">
        {{-- Mobile Toggle --}}
        <button class="navbar-toggle me-1" id="sidebarToggleBtn" onclick="openSidebar()" aria-label="Toggle Sidebar">
            <span class="material-symbols-outlined">menu</span>
        </button>

        {{-- Logo --}}
        <div class="navbar-logo">
            <span class="material-symbols-outlined fill">school</span>
        </div>

        {{-- Brand Text --}}
        <div>
            <div class="navbar-title">UNDIPA MAKASSAR</div>
            <div class="navbar-subtitle">
                @if(config('app.simulation'))
                    Simulation Portal
                @else
                    Secure Academic Portal
                @endif
            </div>
        </div>
    </div>

    {{-- Right: Actions --}}
    <div class="navbar-actions">
        {{-- Encryption / Simulation Badge --}}
        @if(config('app.simulation'))
        <div class="encryption-badge" style="background:rgba(220,38,38,0.1);border-color:#dc2626;color:#dc2626;">
            <span class="dot" style="background:#dc2626;"></span>
            <span class="material-symbols-outlined fill" style="font-size:12px;color:#dc2626;">warning</span>
            <span>ENCRYPTION INACTIVE</span>
        </div>
        @else
        <div class="encryption-badge">
            <span class="dot"></span>
            <span class="material-symbols-outlined fill" style="font-size: 12px; color: #16a34a;">shield</span>
            <span>ENCRYPTION ACTIVE</span>
        </div>
        @endif

        {{-- User Menu --}}
        <div class="user-menu">
            <div class="user-avatar">
                <span class="material-symbols-outlined">person</span>
            </div>
            <div class="user-info">
                <div class="user-name">
                    @if(Auth::check())
                        @if(Auth::user()->isAdmin())
                            Administrator
                        @else
                            {{ Auth::user()->mahasiswa?->nama ?? Auth::user()->nim }}
                        @endif
                    @else
                        Guest
                    @endif
                </div>
                <div class="user-role">
                    {{ Auth::check() ? ucfirst(Auth::user()->role) : 'Guest' }}
                </div>
            </div>
            <span class="material-symbols-outlined" style="font-size:18px; color:var(--color-text-muted)">expand_more</span>
        </div>
    </div>

</header>
