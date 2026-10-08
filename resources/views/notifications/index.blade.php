@extends('layouts.app')
@section('content')

{{-- Page Header --}}
<div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.35rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.6rem;">
            <span style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0;">
                <i class="fa-solid fa-bell"></i>
            </span>
            {{ __('Semua Notifikasi') }}
        </h1>
        <p style="color: #64748b; font-size: 0.85rem; margin-top: 0.35rem; margin-left: 3rem;">{{ __('Histori lengkap seluruh notifikasi dan kejadian dalam sistem') }}</p>
    </div>
</div>

<div class="card anim-slide-up">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-list-ul"></i> {{ __('Daftar Notifikasi') }}
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table" id="table-notifications" style="width:100%;">
                <thead>
                    <tr>
                        <th style="width: 50px;">{{ __('Status') }}</th>
                        <th>{{ __('Judul Notifikasi') }}</th>
                        <th>{{ __('Keterangan / Deskripsi') }}</th>
                        <th>{{ __('Waktu (Relatif)') }}</th>
                        <th>{{ __('Tanggal Kejadian') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $notif)
                        @php
                            $colorClass = $notif->type === 'success' ? '#16a34a' : ($notif->type === 'danger' ? '#dc2626' : '#ea580c');
                            $bgClass = $notif->type === 'success' ? '#dcfce7' : ($notif->type === 'danger' ? '#fee2e2' : '#ffedd5');
                        @endphp
                        <tr style="cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'" onclick="window.location='{{ $notif->link ?? '#' }}'">
                            <td style="text-align: center;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $bgClass }}; color: {{ $colorClass }}; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid {{ $notif->icon }}"></i>
                                </div>
                            </td>
                            <td style="font-weight: 600; color: #1e293b;">
                                <a href="{{ $notif->link ?? '#' }}" style="color: inherit; text-decoration: none;">{{ $notif->title }}</a>
                            </td>
                            <td style="color: #64748b;">{{ $notif->desc }}</td>
                            <td><span style="background: #f1f5f9; padding: 0.3rem 0.6rem; border-radius: 6px; font-size: 0.75rem; color: #475569; font-weight: 600;"><i class="fa-regular fa-clock" style="margin-right: 0.3rem;"></i> {{ $notif->time_human }}</span></td>
                            <td style="color: #64748b; font-weight: 500;">{{ $notif->date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
