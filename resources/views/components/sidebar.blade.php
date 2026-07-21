<aside class="sidebar" id="appSidebar">

    {{-- Sidebar Header --}}
    <div class="sidebar-header">
        <span class="sidebar-header-title">Navigation</span>
    </div>

    {{-- Toggle Bar --}}
    <div class="sidebar-toggle-bar">
        <span class="material-symbols-outlined">menu</span>
    </div>

    {{-- Nav Links --}}
    @php
        // Prefix route aktif untuk pengecekan active state sidebar
        $rp = config('app.simulation') ? 'simulasi.' : '';
    @endphp
    <nav class="sidebar-nav">
        <ul>
            @if(Auth::check() && Auth::user()->isAdmin())
                {{-- Admin Navigation --}}
                <li>
                    <a href="{{ sim_route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs($rp.'admin.dashboard') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs($rp.'admin.dashboard') ? 'fill' : '' }}">dashboard</span>
                        <span class="nav-label">Dashboard Admin</span>
                    </a>
                </li>
                <li>
                    <a href="{{ sim_route('admin.users.index') }}" class="sidebar-nav-item {{ request()->routeIs($rp.'admin.users.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs($rp.'admin.users.*') ? 'fill' : '' }}">group</span>
                        <span class="nav-label">Kelola Akun User</span>
                    </a>
                </li>
                <li>
                    <a href="{{ sim_route('admin.mahasiswas.index') }}" class="sidebar-nav-item {{ request()->routeIs($rp.'admin.mahasiswas.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs($rp.'admin.mahasiswas.*') ? 'fill' : '' }}">person</span>
                        <span class="nav-label">Kelola Mahasiswa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ sim_route('admin.mata-kuliahs.index') }}" class="sidebar-nav-item {{ request()->routeIs($rp.'admin.mata-kuliahs.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs($rp.'admin.mata-kuliahs.*') ? 'fill' : '' }}">book</span>
                        <span class="nav-label">Kelola Mata Kuliah</span>
                    </a>
                </li>
                <li>
                    <a href="{{ sim_route('admin.jadwal-kuliahs.index') }}" class="sidebar-nav-item {{ request()->routeIs($rp.'admin.jadwal-kuliahs.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs($rp.'admin.jadwal-kuliahs.*') ? 'fill' : '' }}">calendar_month</span>
                        <span class="nav-label">Kelola Jadwal</span>
                    </a>
                </li>
                <li>
                    <a href="{{ sim_route('admin.nilais.index') }}" class="sidebar-nav-item {{ request()->routeIs($rp.'admin.nilais.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs($rp.'admin.nilais.*') ? 'fill' : '' }}">grade</span>
                        <span class="nav-label">Kelola Nilai</span>
                    </a>
                </li>
            @else
                {{-- Mahasiswa Navigation --}}
                <li>
                    <a href="{{ sim_route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs($rp.'dashboard') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs($rp.'dashboard') ? 'fill' : '' }}">dashboard</span>
                        <span class="nav-label">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ sim_route('schedule') }}" class="sidebar-nav-item {{ request()->routeIs($rp.'schedule') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs($rp.'schedule') ? 'fill' : '' }}">calendar_month</span>
                        <span class="nav-label">Jadwal Kuliah</span>
                    </a>
                </li>
                <li>
                    <a href="{{ sim_route('transcript') }}" class="sidebar-nav-item {{ request()->routeIs($rp.'transcript') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs($rp.'transcript') ? 'fill' : '' }}">description</span>
                        <span class="nav-label">Transkrip Nilai</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <a href="{{ sim_route('logout') }}" class="sidebar-logout">
            <span class="material-symbols-outlined">logout</span>
            <span>Logout</span>
        </a>
    </div>

</aside>
