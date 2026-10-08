@extends('layouts.app')
@section('content')

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 1.5rem; background: #dcfce7; color: #166534; padding: 1rem; border-radius: 8px; border: 1px solid #bbf7d0;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

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
    .profile-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: stretch; }
    @media (max-width: 900px) {
        .profile-grid { grid-template-columns: 1fr; }
        .profile-form-card { grid-column: auto !important; }
    }
</style>

<div class="profile-grid">
    <!-- Bagian 1: Card Avatar & Info Singkat -->
    <div class="card anim-slide-up" style="display: flex; flex-direction: column; justify-content: center; text-align: center; padding: 3rem 2rem;">
        <div style="margin-bottom: 1.5rem; position: relative; display: inline-block; align-self: center;">
            <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?length=1&name='.urlencode(preg_replace('/[^a-zA-Z0-9\s]/', '', $user->name)).'&size=150&background=e0e7ff&color=4338ca&bold=true' }}" 
                 style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; box-shadow: 0 10px 25px rgba(67, 56, 202, 0.2); border: 4px solid white;" alt="">
            <div style="position: absolute; bottom: 8px; right: 8px; background: var(--success); width: 24px; height: 24px; border-radius: 50%; border: 4px solid white;" title="Aktif"></div>
        </div>
        <h2 style="font-size: 1.75rem; font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">{{ $user->name }}</h2>
        <div style="color: var(--primary); font-weight: 600; font-size: 1rem; margin-bottom: 1.5rem;">
            {{ strtoupper($user->roles->pluck('name')->first() ?? 'USER') }}
        </div>
    </div>

    <!-- Bagian 2: Card Form Pengubahan Data -->
    <div class="card anim-slide-up delay-1 profile-form-card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-user-pen"></i> {{ __('Perbarui Data Profil') }}
            </div>
        </div>
        <div class="card-body" style="padding: 2.5rem;">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <h3 style="font-size: 1.1rem; color: #0f172a; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">{{ __('Informasi Dasar') }}</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="margin: 0;">
                        <label style="font-weight: 600; color: #475569;">{{ __('Nama Lengkap') }}</label>
                        <input type="text" name="name" class="form-control" style="background: #f8fafc;" value="{{ old('name', $user->name) }}" required>
                    </div>
                    
                    <div class="form-group" style="margin: 0;">
                        <label style="font-weight: 600; color: #475569;">{{ __('Alamat Email') }}</label>
                        <input type="email" name="email" class="form-control" style="background: #f8fafc;" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 2.5rem;">
                    <label style="font-weight: 600; color: #475569;">{{ __('Foto Profil (Opsional)') }}</label>
                    <input type="file" name="avatar" class="form-control" style="background: #f8fafc;" accept="image/*">
                    <small style="color: #94a3b8; display: block; margin-top: 0.5rem;">{{ __('Pilih gambar baru (JPG, PNG, GIF) max 2MB untuk mengganti foto saat ini.') }}</small>
                </div>

                <h3 style="font-size: 1.1rem; color: #0f172a; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">{{ __('Keamanan Akun') }}</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div class="form-group" style="margin: 0;">
                        <label style="font-weight: 600; color: #475569;">{{ __('Password Baru') }}</label>
                        <input type="password" name="password" class="form-control" style="background: #f8fafc;" placeholder="{{ __('Minimal 8 karakter') }}">
                        <small style="color: #94a3b8; margin-top: 0.5rem; display: block;">{{ __('Biarkan kosong jika Anda tidak ingin mengubah password.') }}</small>
                    </div>
                    
                    <div class="form-group" style="margin: 0;">
                        <label style="font-weight: 600; color: #475569;">{{ __('Konfirmasi Password Baru') }}</label>
                        <input type="password" name="password_confirmation" class="form-control" style="background: #f8fafc;" placeholder="{{ __('Ketik ulang password baru') }}">
                    </div>
                </div>
                
                <div style="margin-top: 2.5rem; text-align: right;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.85rem 2rem; font-weight: 600; font-size: 1rem; border-radius: 8px;">
                        <i class="fa-solid fa-save" style="margin-right: 0.5rem;"></i> {{ __('Simpan Perubahan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
