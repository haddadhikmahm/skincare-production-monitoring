@extends('layouts.app')
@section('content')

{{-- Page Header --}}
<div style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); border-radius: 20px; padding: 2rem 2.5rem; color: white; margin-bottom: 2rem; box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.4); position: relative; overflow: hidden; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;" class="anim-slide-up">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2; display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 70px; height: 70px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2rem; border: 1px solid rgba(255, 255, 255, 0.3);">
            <i class="fa-solid fa-triangle-exclamation" style="color: white;"></i>
        </div>
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 0.2rem; letter-spacing: -0.5px;">{{ __('Waste & Downtime Log') }}</h1>
            <p style="color: #fecaca; font-size: 0.95rem; margin: 0; max-width: 500px; line-height: 1.4;">{{ __('Laporkan material yang terbuang (waste) dan durasi mesin berhenti (downtime) untuk melacak inefisiensi produksi secara akurat.') }}</p>
        </div>
    </div>
    
    <div style="position: relative; z-index: 2;">
        <div style="background: rgba(0,0,0,0.2); padding: 0.75rem 1.5rem; border-radius: 12px; backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.1); text-align: center;">
            <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #fecaca; margin-bottom: 0.2rem;">{{ __('Total Insiden') }}</div>
            <div style="font-size: 2rem; font-weight: 800; line-height: 1;">{{ $wastes->count() ?? 0 }}</div>
        </div>
    </div>
</div>

{{-- Form Catat Waste --}}
<div class="card anim-slide-up delay-1" style="margin-bottom: 2rem; border-top: 4px solid #ef4444; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <div class="card-header" style="background: #f8fafc; padding: 1.5rem;">
        <div class="card-title">
            <i class="fa-solid fa-clipboard-list" style="color: #ef4444; margin-right: 0.5rem;"></i> {{ __('Form Pencatatan Insiden Baru') }}
        </div>
    </div>
    <div class="card-body" style="padding: 2rem;">
        <form action="{{ route('waste.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 600; color: #334155; margin-bottom: 0.5rem; display: block;"><i class="fa-solid fa-file-invoice" style="color: #94a3b8; margin-right: 0.4rem;"></i> Terkait Work Order <span style="color: #ef4444;">*</span></label>
                    <select name="work_order_id" class="form-control" style="border: 2px solid #e2e8f0; padding: 0.6rem 1rem; border-radius: 8px;" required>
                        <option value="">-- Pilih Work Order --</option>
                        @foreach($workOrders as $wo)
                            <option value="{{ $wo->id }}">{{ $wo->order_number }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 600; color: #334155; margin-bottom: 0.5rem; display: block;"><i class="fa-solid fa-industry" style="color: #94a3b8; margin-right: 0.4rem;"></i> Lokasi Stasiun Kerja <span style="color: #ef4444;">*</span></label>
                    <select name="workstation_id" class="form-control" style="border: 2px solid #e2e8f0; padding: 0.6rem 1rem; border-radius: 8px;" required>
                        <option value="">-- Pilih Stasiun Kerja --</option>
                        @foreach($workstations as $ws)
                            <option value="{{ $ws->id }}">{{ $ws->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 600; color: #334155; margin-bottom: 0.5rem; display: block;"><i class="fa-solid fa-boxes-stacked" style="color: #ef4444; margin-right: 0.4rem;"></i> Jumlah Defect / Waste</label>
                    <div style="position: relative;">
                        <input type="number" name="waste_quantity" class="form-control" style="border: 2px solid #e2e8f0; padding: 0.6rem 1rem; border-radius: 8px; padding-right: 4rem;" placeholder="0" min="0">
                        <span style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem; font-weight: 600;">PCS/GR</span>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 600; color: #334155; margin-bottom: 0.5rem; display: block;"><i class="fa-solid fa-stopwatch" style="color: #f59e0b; margin-right: 0.4rem;"></i> {{ __('Durasi') }} Downtime</label>
                    <div style="position: relative;">
                        <input type="number" name="downtime_minutes" class="form-control" style="border: 2px solid #e2e8f0; padding: 0.6rem 1rem; border-radius: 8px; padding-right: 4rem;" placeholder="0" min="0">
                        <span style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem; font-weight: 600;">MENIT</span>
                    </div>
                </div>
                
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem; margin-bottom: 0;">
                <label style="font-weight: 600; color: #334155; margin-bottom: 0.5rem; display: block;"><i class="fa-solid fa-comment-dots" style="color: #94a3b8; margin-right: 0.4rem;"></i> Deskripsi / Penyebab Insiden <span style="color: #ef4444;">*</span></label>
                <textarea name="reason" class="form-control" rows="2" style="border: 2px solid #e2e8f0; padding: 0.8rem 1rem; border-radius: 8px; resize: none;" placeholder="Contoh: Mesin sealer macet tiba-tiba, kemasan bocor karena suhu heater melebihi standar..." required></textarea>
            </div>
            
            <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px dashed #cbd5e1;">
                <button type="submit" class="btn" style="background: #ef4444; color: white; padding: 0.8rem 2rem; font-weight: 700; border-radius: 8px; font-size: 1rem; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);">
                    <i class="fa-solid fa-paper-plane" style="margin-right: 0.5rem;"></i>{{ __('Simpan Laporan') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- Log Histori --}}
<div class="card anim-slide-up delay-2">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-table-list" style="color: #3b82f6; margin-right: 0.4rem;"></i>
            {{ __('Riwayat Insiden Terakhir') }}
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table id="table-waste" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0; text-align: left; background: #f8fafc; font-size: 0.75rem; color: #64748b; text-transform: uppercase;">{{ __('Stasiun Kerja') }}</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0; text-align: left; background: #f8fafc; font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Penyebab Insiden</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0; text-align: center; background: #f8fafc; font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Defect Qty</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0; text-align: center; background: #f8fafc; font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Downtime</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0; text-align: center; background: #f8fafc; font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Status Dampak</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0; text-align: center; background: #f8fafc; font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Dilaporkan Pada</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0; text-align: center; background: #f8fafc; font-size: 0.75rem; color: #64748b; text-transform: uppercase;">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wastes as $w)
                    @php
                        $wasteQty = $w->defects_count ?? 0;
                        $downtime = $w->downtime_minutes ?? 0;
                        $severity = ($wasteQty > 100 || $downtime > 60) ? 'high' : (($wasteQty > 30 || $downtime > 20) ? 'medium' : 'low');
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 32px; height: 32px; background: #fff1f2; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-industry" style="color: #ef4444; font-size: 0.8rem;"></i>
                                </div>
                                <span style="font-weight: 600; color: #1e293b; font-size: 0.9rem;">{{ $w->workstation->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="color: #334155; font-size: 0.85rem; max-width: 250px; font-weight: 500;" title="{{ $w->downtime_reason }}">
                                {{ \Illuminate\Support\Str::limit($w->downtime_reason, 50) }}
                            </div>
                            <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 0.2rem;">Oleh: {{ $w->reporter->name ?? 'Unknown' }}</div>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            @if($wasteQty > 0)
                                <div style="font-weight: 800; color: #ef4444; font-size: 1rem;">{{ number_format($wasteQty, 0, ',', '.') }}</div>
                                <div style="font-size: 0.65rem; color: #94a3b8; font-weight: 600;">PCS / GRAM</div>
                            @else
                                <span style="color: #cbd5e1; font-size: 0.8rem;">—</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            @if($downtime > 0)
                                <div style="font-weight: 800; color: #f59e0b; font-size: 1rem;">{{ $downtime >= 60 ? floor($downtime/60).'j ' . ($downtime%60).'m' : $downtime . ' m' }}</div>
                                <div style="font-size: 0.65rem; color: #94a3b8; font-weight: 600;">WAKTU HENTI</div>
                            @else
                                <span style="color: #cbd5e1; font-size: 0.8rem;">—</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            @if($severity == 'high')
                                <span style="background: #fee2e2; color: #b91c1c; padding: 0.3rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700;"><i class="fa-solid fa-circle-exclamation"></i> KRITIS</span>
                            @elseif($severity == 'medium')
                                <span style="background: #fef3c7; color: #b45309; padding: 0.3rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700;"><i class="fa-solid fa-circle-dot"></i> SEDANG</span>
                            @else
                                <span style="background: #f1f5f9; color: #475569; padding: 0.3rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700;"><i class="fa-solid fa-check-circle"></i> RENDAH</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center; color: #64748b; font-size: 0.85rem;">
                            {{ $w->created_at->format('d M Y') }}<br>
                            <span style="font-size: 0.75rem;">{{ $w->created_at->format('H:i') }}</span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <form action="{{ route('waste.destroy', $w->id) }}" method="POST" class="form-delete" data-message="Hapus log insiden ini?">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-action btn-action-delete btn-delete-trigger" title="Hapus Log">
                                    <i class="fa-solid fa-trash"></i> {{ __('Hapus') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
