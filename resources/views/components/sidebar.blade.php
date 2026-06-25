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
    <nav class="sidebar-nav">
        <ul>
            @if(Auth::check() && Auth::user()->isAdmin())
                {{-- Admin Navigation --}}
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('admin.dashboard') ? 'fill' : '' }}">dashboard</span>
                        <span class="nav-label">Dashboard Admin</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('admin.users.*') ? 'fill' : '' }}">group</span>
                        <span class="nav-label">Kelola Akun User</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.mahasiswas.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.mahasiswas.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('admin.mahasiswas.*') ? 'fill' : '' }}">person</span>
                        <span class="nav-label">Kelola Mahasiswa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.mata-kuliahs.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.mata-kuliahs.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('admin.mata-kuliahs.*') ? 'fill' : '' }}">book</span>
                        <span class="nav-label">Kelola Mata Kuliah</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.jadwal-kuliahs.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.jadwal-kuliahs.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('admin.jadwal-kuliahs.*') ? 'fill' : '' }}">calendar_month</span>
                        <span class="nav-label">Kelola Jadwal</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.nilais.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.nilais.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('admin.nilais.*') ? 'fill' : '' }}">grade</span>
                        <span class="nav-label">Kelola Nilai</span>
                    </a>
                </li>
            @else
                {{-- Mahasiswa Navigation --}}
                <li>
                    <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'fill' : '' }}">dashboard</span>
                        <span class="nav-label">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('schedule') }}" class="sidebar-nav-item {{ request()->routeIs('schedule') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('schedule') ? 'fill' : '' }}">calendar_month</span>
                        <span class="nav-label">Jadwal Kuliah</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transcript') }}" class="sidebar-nav-item {{ request()->routeIs('transcript') ? 'active' : '' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('transcript') ? 'fill' : '' }}">description</span>
                        <span class="nav-label">Transkrip Nilai</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <a href="{{ route('logout') }}" class="sidebar-logout">
            <span class="material-symbols-outlined">logout</span>
            <span>Logout</span>
        </a>
    </div>

</aside>
