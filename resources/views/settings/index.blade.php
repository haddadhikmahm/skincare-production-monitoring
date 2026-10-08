@extends('layouts.app')
@section('content')
<div class="card anim-slide-up">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-sliders"></i> {{ __('Pengaturan Sistem (Settings)') }}</div></div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 1.5rem; background: #dcfce7; color: #166534; padding: 1rem; border-radius: 8px; border: 1px solid #bbf7d0;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            <div style="display:flex; flex-direction:column; gap:2.5rem;">
                <div>
                    <h3 style="font-size:1.1rem; margin-bottom:1.5rem; color:var(--sidebar-bg); border-bottom:1px solid var(--border-color); padding-bottom:0.75rem;"><i class="fa-solid fa-user-gear"></i> {{ __('Preferensi Pengguna') }}</h3>
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem;">
                        <div class="form-group" style="margin:0;">
                            <label>{{ __('Bahasa Antarmuka') }}</label>
                            <select name="language" class="form-control">
                                <option value="id" {{ session('language', 'id') == 'id' ? 'selected' : '' }}>{{ __('Bahasa Indonesia (ID)') }}</option>
                                <option value="en" {{ session('language') == 'en' ? 'selected' : '' }}>{{ __('English (EN)') }}</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label>{{ __('Mode Tema Warna') }}</label>
                            <select name="theme" class="form-control">
                                <option value="light" {{ session('theme', 'light') == 'light' ? 'selected' : '' }}>{{ __('Terang (Light Mode)') }}</option>
                                <option value="dark" {{ session('theme') == 'dark' ? 'selected' : '' }}>{{ __('Gelap (Dark Mode)') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                @can('manage_settings')
                <div>
                    <h3 style="font-size:1.1rem; margin-bottom:1.5rem; color:var(--sidebar-bg); border-bottom:1px solid var(--border-color); padding-bottom:0.75rem;"><i class="fa-solid fa-building"></i> {{ __('Profil Perusahaan (Admin)') }}</h3>
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem;">
                        <div class="form-group" style="margin:0;"><label>{{ __('Nama Perusahaan') }}</label><input type="text" name="company_name" class="form-control" value="PT. Sinar Alfa Omega"></div>
                        <div class="form-group" style="margin:0;"><label>{{ __('Email Kontak Sistem') }}</label><input type="text" name="company_email" class="form-control" value="admin@sinaralfaomega.com"></div>
                    </div>
                </div>
                
                <div>
                    <h3 style="font-size:1.1rem; margin-bottom:1.5rem; color:var(--sidebar-bg); border-bottom:1px solid var(--border-color); padding-bottom:0.75rem;"><i class="fa-solid fa-sliders"></i> {{ __('Konfigurasi Batas Toleransi Produksi (Admin)') }}</h3>
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem;">
                        <div class="form-group" style="margin:0;"><label>{{ __('Batas Kritis Waste (%)') }}</label><input type="number" name="waste_limit" class="form-control" value="5"></div>
                        <div class="form-group" style="margin:0;"><label>{{ __('Target Minimal OEE (%)') }}</label><input type="number" name="oee_target" class="form-control" value="85"></div>
                    </div>
                    <p style="font-size: 0.8rem; color: #64748b; margin-top: 0.5rem;">{{ __('Jika persentase Waste melewati batas 5%, sistem akan mengirimkan notifikasi peringatan berlabel merah kepada Supervisor.') }}</p>
                </div>
                @endcan
                
                <div style="border-top:1px solid var(--border-color); padding-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;"><i class="fa-solid fa-save"></i> {{ __('Simpan Pengaturan') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
