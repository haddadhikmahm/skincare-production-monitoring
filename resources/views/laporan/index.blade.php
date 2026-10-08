@extends('layouts.app')
@section('content')

{{-- Page Header --}}
<div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.75rem; letter-spacing: -0.5px;">
            <div style="width: 42px; height: 42px; background: #e0e7ff; color: #4338ca; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            {{ __('Laporan Produksi Terpadu') }}
        </h1>
        <p style="color: #64748b; font-size: 0.9rem; margin-top: 0.4rem; margin-left: 3.5rem;">
            Ringkasan data historis operasional, siap untuk diekspor ke format dokumen atau spreadsheet.
        </p>
    </div>
    
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('laporan.pdf', request()->all()) }}" target="_blank" class="btn" style="background: white; color: #dc2626; border: 1px solid #fecaca; padding: 0.6rem 1.25rem; font-size: 0.85rem; border-radius: 8px; font-weight: 600; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
            <i class="fa-solid fa-file-pdf" style="margin-right: 0.4rem;"></i> {{ __('Unduh PDF') }}
        </a>
        <a href="{{ route('laporan.excel', request()->all()) }}" class="btn" style="background: #10b981; color: white; border: 1px solid #059669; padding: 0.6rem 1.25rem; font-size: 0.85rem; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 12px rgba(16,185,129,0.2);">
            <i class="fa-solid fa-file-excel" style="margin-right: 0.4rem;"></i> Unduh Excel
        </a>
    </div>
</div>

{{-- Filter Form --}}
<div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
    <div style="font-weight: 600; color: #475569; font-size: 0.9rem; margin-right: 1rem;">
        <i class="fa-solid fa-filter" style="margin-right: 0.4rem;"></i> Filter Laporan:
    </div>
    <form action="{{ route('laporan.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; flex: 1; align-items: center;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <label style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Dari</label>
            <input type="date" name="date_start" value="{{ request('date_start') }}" class="form-control" style="padding: 0.4rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.85rem;">
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <label style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Sampai</label>
            <input type="date" name="date_end" value="{{ request('date_end') }}" class="form-control" style="padding: 0.4rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.85rem;">
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <label style="font-size: 0.8rem; color: #64748b; font-weight: 600;">{{ __('Status') }}</label>
            <select name="status" class="form-control" style="padding: 0.4rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.85rem; width: 140px;">
                <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>Semua Status</option>
                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>{{ __('Selesai') }}</option>
                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>Sedang Berjalan</option>
            </select>
        </div>
        <div style="display: flex; gap: 0.5rem; margin-left: auto;">
            <button type="submit" class="btn" style="background: #3b82f6; color: white; padding: 0.45rem 1rem; font-size: 0.85rem; border-radius: 6px;">{{ __('Terapkan Filter') }}</button>
            <a href="{{ route('laporan.index') }}" class="btn" style="background: #f1f5f9; color: #475569; padding: 0.45rem 1rem; font-size: 0.85rem; border-radius: 6px; text-decoration: none; border: 1px solid #cbd5e1;">Reset</a>
        </div>
    </form>
</div>

{{-- Report Meta Info --}}
<div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 1.5rem 2rem; margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 3rem; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <div style="display: flex; flex-direction: column; gap: 0.4rem;">
        <span style="font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Laporan</span>
        <span style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
    </div>
    <div style="display: flex; flex-direction: column; gap: 0.4rem;">
        <span style="font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Total Data Terekam</span>
        <span style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">{{ count($processes) }} Transaksi Produksi</span>
    </div>
    <div style="display: flex; flex-direction: column; gap: 0.4rem;">
        <span style="font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Ruang Lingkup Akses</span>
        <span style="font-size: 0.95rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.4rem;">
            <i class="fa-solid fa-shield-halved" style="color: #3b82f6;"></i>
            {{ auth()->user()->roles->pluck('name')->first() ?? 'User' }}
        </span>
    </div>
    <div style="display: flex; flex-direction: column; gap: 0.4rem; margin-left: auto;">
        <span style="font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Status Generate</span>
        <span style="font-size: 0.85rem; font-weight: 600; color: #15803d; background: #dcfce7; padding: 0.2rem 0.6rem; border-radius: 6px; display: inline-block;">Real-time Terhubung</span>
    </div>
</div>

{{-- Data Table --}}
<div class="table-wrapper anim-fade-in" style="border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; background: white; box-shadow: 0 4px 24px rgba(15,23,42,0.04);">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; background: #fafbfc; display: flex; align-items: center; gap: 0.75rem;">
        <i class="fa-solid fa-table" style="color: #64748b;"></i>
        <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0;">Pratinjau Data Laporan</h3>
    </div>
    <div class="table-responsive">
        <table class="table" id="table-laporan" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th># ID</th>
                    <th>Nomor SPK</th>
                    <th>Stasiun Kerja (Workstation)</th>
                    <th>Operator Penanggung Jawab</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Status Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($processes as $p)
                <tr>
                    <td style="color: #64748b; font-weight: 600; font-size: 0.85rem;">#{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td style="font-weight: 700; color: #0f172a;">{{ $p->workOrder ? $p->workOrder->order_number : '-' }}</td>
                    <td style="color: #475569; font-weight: 500;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-solid fa-industry" style="color: #94a3b8; font-size: 0.8rem;"></i>
                            {{ $p->workstation ? $p->workstation->name : '-' }}
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            <div style="width: 26px; height: 26px; background: #e0e7ff; color: #4338ca; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700;">
                                {{ substr($p->operator ? $p->operator->name : '?', 0, 1) }}
                            </div>
                            <span style="font-size: 0.85rem; font-weight: 600; color: #334155;">{{ $p->operator ? $p->operator->name : '-' }}</span>
                        </div>
                    </td>
                    <td>
                        <span style="background: #f1f5f9; padding: 0.3rem 0.6rem; border-radius: 6px; font-size: 0.75rem; color: #475569; font-weight: 600;">
                            {{ \Carbon\Carbon::parse($p->start_time)->format('d M Y, H:i') }}
                        </span>
                    </td>
                    <td>
                        @if($p->finish_time)
                            <span style="background: #f1f5f9; padding: 0.3rem 0.6rem; border-radius: 6px; font-size: 0.75rem; color: #475569; font-weight: 600;">
                                {{ \Carbon\Carbon::parse($p->finish_time)->format('d M Y, H:i') }}
                            </span>
                        @else
                            <span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;">Belum selesai</span>
                        @endif
                    </td>
                    <td>
                        @if($p->status == 'Completed')
                            <span class="flow-badge badge-selesai">{{ __('Selesai') }}</span>
                        @else
                            <span class="flow-badge badge-berjalan">Sedang Berjalan</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
