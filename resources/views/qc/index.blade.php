@extends('layouts.app')
@section('content')

{{-- Page Header --}}
<div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.35rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.6rem;">
            <span style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0;">
                <i class="fa-solid fa-flask"></i>
            </span>
            {{ __('Quality Control & Quality Release') }}
        </h1>
        <p style="color: #64748b; font-size: 0.85rem; margin-top: 0.35rem; margin-left: 3rem;">{{ __('Verifikasi mutu produk sebelum direlease ke tahap berikutnya') }}</p>
    </div>
</div>

{{-- Form Input QC --}}
<div class="card anim-slide-up" style="margin-bottom: 1.5rem; overflow: hidden;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-microscope" style="color: #3b82f6; margin-right: 0.4rem;"></i>
            {{ __('Input Hasil Quality Control') }}
        </div>
    </div>
    <div class="card-body" style="padding: 1.5rem;">
        <form action="{{ route('qc.store') }}" method="POST">
            @csrf
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 200px;">
                    <label><i class="fa-solid fa-file-invoice" style="color: #94a3b8; margin-right: 0.3rem;"></i> Pilih Work Order</label>
                    <select name="work_order_id" class="form-control" required>
                        <option value="">-- Pilih Work Order --</option>
                        @foreach($workOrders as $wo)
                            <option value="{{ $wo->id }}">{{ $wo->order_number }} — {{ $wo->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="flex: 1; min-width: 180px;">
                    <label><i class="fa-solid fa-clipboard-check" style="color: #94a3b8; margin-right: 0.3rem;"></i> {{ __('Status') }} QC</label>
                    <select name="status" class="form-control" required>
                        <option value="Lulus">✅ Lulus (Approved / Release)</option>
                        <option value="Tahan">⚠️ Tahan (Hold / Reject)</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label><i class="fa-solid fa-notes-medical" style="color: #94a3b8; margin-right: 0.3rem;"></i> Catatan / Parameter Khusus (Opsional)</label>
                <input type="text" name="notes" class="form-control"
                       placeholder="Cth: Viskositas 12.000 cPs, warna sesuai standar RAL, pH 6.5">
            </div>
            <div style="display: flex; align-items: center; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.5rem; font-weight: 600;">
                    <i class="fa-solid fa-circle-check"></i>{{ __('Simpan Hasil QC') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Riwayat QC --}}
<div class="card anim-slide-up delay-1" style="overflow: hidden;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-table-list" style="color: #3b82f6; margin-right: 0.4rem;"></i>
            {{ __('Riwayat Quality Release') }}
        </div>
        <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">{{ $qcs->count() ?? 0 }} entri tercatat</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table id="table-qc">
                <thead>
                    <tr>
                        <th>Work Order</th>
                        <th>Waktu Verifikasi</th>
                        <th>Status QC</th>
                        <th>Catatan / Parameter</th>
                        <th>Analis / Verifikator</th>
                        <th style="text-align: center;">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($qcs as $qc)
                    <tr>
                        <td>
                            <span style="font-family: 'Courier New', monospace; font-weight: 700; font-size: 0.83rem; color: #0f172a; background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 5px;">
                                {{ $qc->workOrder->order_number ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem;">
                                <div style="font-weight: 600; color: #1e293b;">
                                    {{ \Carbon\Carbon::parse($qc->verified_at)->format('d/m/Y') }}
                                </div>
                                <div style="font-size: 0.73rem; color: #94a3b8;">
                                    {{ \Carbon\Carbon::parse($qc->verified_at)->format('H:i') }} WIB
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="flow-badge {{ $qc->status == 'Lulus' ? 'badge-berjalan' : 'badge-hold' }}">
                                @if($qc->status == 'Lulus')
                                    <i class="fa-solid fa-check" style="font-size: 0.6rem;"></i>
                                @else
                                    <i class="fa-solid fa-pause" style="font-size: 0.6rem;"></i>
                                @endif
                                {{ $qc->status }}
                            </span>
                        </td>
                        <td>
                            @if($qc->notes)
                                <span style="color: #475569; font-size: 0.85rem; max-width: 280px; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $qc->notes }}">
                                    {{ $qc->notes }}
                                </span>
                            @else
                                <span style="color: #cbd5e1; font-size: 0.8rem; font-style: italic;">— Tidak ada catatan</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.6rem;">
                                <img src="https://ui-avatars.com/api/?length=1&name={{ urlencode(preg_replace('/[^a-zA-Z0-9\s]/', '', $qc->verifiedBy->name ?? 'N')) }}&background=ede9fe&color=7c3aed&size=28&bold=true"
                                     alt="Verifikator"
                                     style="width: 28px; height: 28px; border-radius: 7px;">
                                <span style="font-weight: 500; font-size: 0.85rem; color: #1e293b;">{{ $qc->verifiedBy->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="action-group" style="justify-content: center;">
                                <form action="{{ route('qc.destroy', $qc->id) }}" method="POST"
                                      class="form-delete" data-message="Hapus data QC ini?" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-action btn-action-delete btn-delete-trigger" title="Hapus Data QC">
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
