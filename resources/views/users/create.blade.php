@extends('layouts.app')
@section('content')
<div class="card anim-slide-up">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-user-plus"></i> {{ __('Tambah Pengguna') }} Baru</div>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 1.5rem; background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; border: 1px solid #fecaca;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <style>
            .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
            @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
        </style>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>{{ __('Email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom:0; grid-column: 1 / -1;">
                    <label>Role / Hak Akses</label>
                    <select name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}" {{ old('role') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div style="display:flex; gap:1rem; margin-top:2rem; padding-top:1.5rem; border-top:1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;"><i class="fa-solid fa-save"></i>{{ __('Simpan Pengguna') }}</button>
                <a href="{{ route('users.index') }}" class="btn" style="background:#f1f5f9; color:#475569; padding: 0.75rem 1.5rem;"><i class="fa-solid fa-arrow-left"></i> Batal & Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
