@extends('layouts.app')
@section('content')

{{-- Header Work Order --}}
<div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.35rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.6rem;">
            <span style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0;">
                <i class="fa-solid fa-file-invoice"></i>
            </span>
            {{ __('Manajemen Work Order') }}
        </h1>
        <p style="color: #64748b; font-size: 0.85rem; margin-top: 0.35rem; margin-left: 3rem;">{{ __('Daftar Surat Perintah Kerja (SPK) produksi') }}</p>
    </div>
    <a href="{{ route('work-orders.create') }}" class="btn btn-primary" style="padding: 0.65rem 1.25rem; align-self: flex-start;">
        <i class="fa-solid fa-plus"></i> {{ __('Tambah Work Order') }}
    </a>
</div>

{{-- Alert Session --}}
@if(session('success'))
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

{{-- Table Card --}}
<div class="card anim-slide-up" style="overflow: hidden;">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table id="table-work-orders">
                <thead>
                    <tr>
                        <th>{{ __('No. WO') }}</th>
                        <th>{{ __('Nama Produk') }}</th>
                        <th>{{ __('Qty Target') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Tanggal Dibuat') }}</th>
                        <th style="text-align: center;">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workOrders as $wo)
                    <tr>
                        <td>
                            <span style="font-family: 'Courier New', monospace; font-weight: 700; font-size: 0.85rem; color: #0f172a; background: #f1f5f9; padding: 0.2rem 0.55rem; border-radius: 5px; letter-spacing: 0.3px;">
                                {{ $wo->order_number }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.6rem;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $wo->status == 'Completed' ? '#10b981' : ($wo->status == 'In Progress' ? '#3b82f6' : '#94a3b8') }}; flex-shrink: 0;"></span>
                                <span style="font-weight: 600; color: #1e293b;">{{ $wo->product_name }}</span>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight: 700; color: #1e293b;">{{ number_format($wo->quantity, 0, ',', '.') }}</span>
                            <span style="color: #94a3b8; font-size: 0.78rem; font-weight: 500;"> pcs</span>
                        </td>
                        <td>
                            <span class="flow-badge {{ $wo->status == 'Completed' ? 'badge-berjalan' : ($wo->status == 'In Progress' ? 'badge-selesai' : 'badge-menunggu') }}">
                                {{ $wo->status }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem; color: #475569;">
                                <i class="fa-regular fa-calendar" style="color: #94a3b8; margin-right: 0.3rem; font-size: 0.75rem;"></i>
                                {{ \Carbon\Carbon::parse($wo->created_at)->format('d/m/Y') }}
                                <span style="color: #94a3b8; font-size: 0.75rem;"> {{ \Carbon\Carbon::parse($wo->created_at)->format('H:i') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="action-group" style="justify-content: center;">
                                <a href="{{ route('work-orders.edit', $wo->id) }}" class="btn-action" style="color: #3b82f6; border-color: #bfdbfe; background: #eff6ff;" title="Edit WO">
                                    <i class="fa-solid fa-pen-to-square"></i> {{ __('Edit') }}
                                </a>
                                <form action="{{ route('work-orders.destroy', $wo->id) }}" method="POST"
                                      class="form-delete" data-message="Yakin ingin menghapus Work Order {{ $wo->order_number }}?"
                                      style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-action btn-action-delete btn-delete-trigger" title="Hapus WO">
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
@endsection
