@extends('layouts.app')
@section('content')
<div class="card anim-slide-up" style="overflow: hidden;">
    <div class="card-header">
        <div>
            <div class="card-title">
                <i class="fa-solid fa-users-gear" style="color: #3b82f6; margin-right: 0.5rem;"></i> {{ __('Manajemen Pengguna') }}
            </div>
            <p style="color: #64748b; font-size: 0.8rem; margin-top: 0.25rem;">{{ __('Kelola akun dan hak akses pengguna sistem') }}</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary" id="btn-tambah-user">
            <i class="fa-solid fa-user-plus"></i> {{ __('Tambah Pengguna') }}
        </a>
    </div>

    <div class="card-body" style="padding: 0;">
        <div class="dataTables_wrapper">
            <div class="table-responsive">
                <table id="table-users">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Pengguna</th>
                            <th>{{ __('Email') }}</th>
                            <th>Role / Hak Akses</th>
                            <th style="text-align: center; width: 120px;">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td style="text-align: center; color: #94a3b8; font-weight: 700; font-size: 0.8rem;">
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="https://ui-avatars.com/api/?length=1&name={{ urlencode(preg_replace('/[^a-zA-Z0-9\s]/', '', $user->name)) }}&background=e0e7ff&color=4338ca&size=36&bold=true"
                                         alt="{{ $user->name }}"
                                         style="width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;">
                                    <div>
                                        <div style="font-weight: 600; color: #0f172a; font-size: 0.875rem;">{{ $user->name }}</div>
                                        <div style="font-size: 0.75rem; color: #94a3b8;">ID #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="display: flex; align-items: center; gap: 0.4rem; color: #475569; font-size: 0.875rem;">
                                    <i class="fa-regular fa-envelope" style="color: #94a3b8; font-size: 0.75rem;"></i>
                                    {{ $user->email }}
                                </span>
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="flow-badge {{ $role->name == 'admin' ? 'badge-selesai' : ($role->name == 'supervisor' ? 'badge-berjalan' : 'badge-menunggu') }}">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                                @if($user->roles->isEmpty())
                                    <span class="flow-badge badge-menunggu">— Tidak ada role</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-group" style="justify-content: center;">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn-action btn-action-edit" title="Edit Pengguna" style="color: #3b82f6; border-color: #bfdbfe; background: #eff6ff; display: inline-flex; align-items: center; gap: 0.3rem;">
                                        <i class="fa-solid fa-pen-to-square"></i> {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" class="form-delete" data-message="Yakin ingin menghapus pengguna {{ $user->name }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action btn-action-delete btn-delete-trigger" title="Hapus Pengguna">
                                            <i class="fa-solid fa-trash"></i> {{ __('Hapus') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
