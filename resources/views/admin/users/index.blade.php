@extends('layouts.app')

@section('title', 'Kelola Akun User')
@section('page_title', 'Kelola Akun User')

@section('content')

@if(session('success'))
<div class="alert alert-success mb-4">
    <span class="material-symbols-outlined">check_circle</span>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger mb-4">
    <span class="material-symbols-outlined">error</span>
    {{ session('error') }}
</div>
@endif

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-title">
            <span class="material-symbols-outlined fill">group</span>
            Daftar Akun
        </div>
        <a href="{{ sim_route('admin.users.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Tambah Akun
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NIM</th>
                        <th>Role</th>
                        <th>Dibuat Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><strong>{{ $user->nim }}</strong></td>
                        <td>
                            @if($user->isAdmin())
                                <span class="badge badge-primary">Admin</span>
                            @else
                                <span class="badge badge-success">Mahasiswa</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ sim_route('admin.users.edit', $user) }}" class="btn btn-sm btn-secondary" title="Edit">
                                    <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                </a>
                                <form action="{{ sim_route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada data akun.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
