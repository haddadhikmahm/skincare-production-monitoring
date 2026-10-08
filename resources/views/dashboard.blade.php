@extends('layouts.app')

@section('content')
<div class="dashboard-grid">
    <!-- Left Column (Main) -->
    <div class="col-main">
        
        <!-- Welcome Banner -->
        <div class="welcome-banner anim-slide-up">
            <h2>{{ __('Selamat Datang') }}, {{ Auth::user()->name }}</h2>
            <p>{{ __('Sistem Informasi Manajemen Produksi Maklon Kosmetik') }}</p>
            <div class="date-badge">
                <i class="fa-regular fa-calendar"></i> {{ now()->translatedFormat('l, d F Y | H:i') }} WIB
            </div>
        </div>

        <!-- 4 Stat Cards -->
        @canany(['manage_work_orders', 'view_vsm'])
        <div class="stat-grid anim-slide-up delay-1">
            <div class="stat-card blue">
                <div class="stat-title">{{ __('Total Work Order') }}</div>
                <div class="stat-value">{{ $totalWorkOrders }}</div>
                <div style="font-size: 0.8rem; margin-top:0.5rem; display:flex; justify-content:space-between;">{{ __('Pesanan') }} <i class="fa-solid fa-arrow-right"></i></div>
                <i class="fa-solid fa-file-invoice icon-bg"></i>
            </div>
            <div class="stat-card green">
                <div class="stat-title">{{ __('Produksi Berjalan') }}</div>
                <div class="stat-value">{{ $inProgressWO }}</div>
                <div style="font-size: 0.8rem; margin-top:0.5rem; display:flex; justify-content:space-between;">{{ __('Work Order') }} <i class="fa-solid fa-arrow-right"></i></div>
                <i class="fa-solid fa-gear icon-bg"></i>
            </div>
            <div class="stat-card purple">
                <div class="stat-title">{{ __('Produksi Selesai') }}</div>
                <div class="stat-value">{{ $completedWO }}</div>
                <div style="font-size: 0.8rem; margin-top:0.5rem; display:flex; justify-content:space-between;">{{ __('Work Order') }} <i class="fa-solid fa-arrow-right"></i></div>
                <i class="fa-solid fa-circle-check icon-bg"></i>
            </div>
            <div class="stat-card red">
                <div class="stat-title">{{ __('Pending / Bottleneck') }}</div>
                <div class="stat-value">{{ $pendingWO }}</div>
                <div style="font-size: 0.8rem; margin-top:0.5rem; display:flex; justify-content:space-between;">{{ __('Work Order') }} <i class="fa-solid fa-arrow-right"></i></div>
                <i class="fa-solid fa-triangle-exclamation icon-bg"></i>
            </div>
        </div>
        @endcanany

        <!-- Alur Produksi Real-time -->
        @canany(['manage_produksi', 'view_vsm'])
        <div class="card anim-slide-up delay-2">
            <div class="card-header">
                <div class="card-title">{{ __('Alur Produksi Real-time') }} <span style="font-size: 0.7rem; background: #dcfce7; color: #166534; padding: 0.2rem 0.5rem; border-radius: 99px; margin-left: 0.5rem; animation: pulse 2s infinite;"><i class="fa-solid fa-circle" style="font-size:0.5rem;"></i> Live</span></div>
                <a href="{{ route('produksi.index') }}" style="font-size:0.8rem; color:var(--primary);">{!! __('Lihat Detail &rarr;') !!}</a>
            </div>
            <div class="card-body flow-container">
                @foreach($workstations as $index => $ws)
                @php
                    // Simulate status randomly for demo dynamics based on index
                    $statusArr = ['Berjalan', 'Bottleneck', 'Menunggu', 'Selesai'];
                    $status = $index == 0 || $index == 1 ? 'Berjalan' : ($index == 3 ? 'Bottleneck' : 'Menunggu');
                    $badgeClass = 'badge-' . strtolower($status);
                    
                    // Icons based on string matching
                    $icon = 'fa-gear';
                    $dummyUnit = 'pcs';
                    if(str_contains(strtolower($ws->name), 'mix')) { $icon = 'fa-blender'; $dummyUnit = 'kg'; }
                    if(str_contains(strtolower($ws->name), 'fill')) $icon = 'fa-bottle-water';
                    if(str_contains(strtolower($ws->name), 'cap')) $icon = 'fa-prescription-bottle';
                    if(str_contains(strtolower($ws->name), 'label')) $icon = 'fa-tag';
                    if(str_contains(strtolower($ws->name), 'pack')) $icon = 'fa-box-open';
                    if(str_contains(strtolower($ws->name), 'finish')) $icon = 'fa-box';
                @endphp
                <div class="flow-step">
                    <div class="flow-icon" @if($status == 'Bottleneck') style="color:var(--danger); background:#fee2e2;" @endif>
                        <i class="fa-solid {{ $icon }}"></i>
                    </div>
                    <div class="flow-name">{{ $index + 1 }}. {{ $ws->name }}</div>
                    <span class="flow-badge {{ $badgeClass }}">{{ $status }}</span>
                    @if($status == 'Berjalan' || $status == 'Bottleneck')
                        <div class="flow-time" @if($status == 'Bottleneck') style="color:var(--danger);" @endif>01:25:32</div>
                        <div class="flow-detail">Batch: B-0325<br>Target: 1.000 {{ $dummyUnit }}</div>
                    @else
                        <div class="flow-time">-</div>
                        <div class="flow-detail">Batch: -<br>Target: 1.000 {{ $dummyUnit }}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endcanany

        <!-- Work Order Terbaru Table -->
        <div class="card anim-slide-up delay-3">
            <div class="card-header">
                <div class="card-title">{{ __('Work Order Terbaru') }}</div>
                @can('manage_work_orders')
                <a href="{{ url('/work-orders') }}" style="font-size:0.8rem; color:var(--primary);">{!! __('Lihat Semua &rarr;') !!}</a>
                @endcan
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('No. WO') }}</th>
                                <th>{{ __('Nama Produk') }}</th>
                                <th>{{ __('Qty') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Tanggal Mulai') }}</th>
                                <th>{{ __('Tanggal Selesai') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workOrders as $wo)
                            <tr>
                                <td style="font-weight: 600;">{{ $wo->order_number }}</td>
                                <td>{{ $wo->product_name }}</td>
                                <td>{{ number_format($wo->quantity, 0, ',', '.') }}</td>
                                <td>
                                    @if($wo->status == 'In Progress')
                                        <span style="color: #15803d; background: #dcfce7; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Berjalan</span>
                                    @elseif($wo->status == 'Completed')
                                        <span style="color: #1d4ed8; background: #eff6ff; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">{{ __('Selesai') }}</span>
                                    @else
                                        <span style="color: #c2410c; background: #fff7ed; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">{{ $wo->status }}</span>
                                    @endif
                                </td>
                                <td style="color: #64748b;">{{ \Carbon\Carbon::parse($wo->created_at)->format('d/m/Y H:i') }}</td>
                                <td style="color: #64748b;">{{ $wo->status == 'Completed' ? \Carbon\Carbon::parse($wo->created_at)->addHours(4)->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column (Sidebar) -->
    <div class="col-side">
        
        <!-- Notifikasi -->
        <div class="card anim-slide-up delay-1">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-bell"></i> {{ __('Notifikasi Terbaru') }}</div>
                <a href="{{ route('notifications.index') }}" style="font-size:0.8rem; color:var(--primary);">{!! __('Lihat Semua &rarr;') !!}</a>
            </div>
            <div class="card-body notification-list" id="dashboardNotifList">
                <div style="padding: 2rem; text-align: center; color: #94a3b8; font-size: 0.85rem;">Memuat notifikasi...</div>
            </div>
        </div>

        <!-- Quick Action -->
        @canany(['manage_work_orders', 'manage_qc', 'manage_waste', 'view_laporan'])
        <div class="card anim-slide-up delay-2">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-bolt"></i> {{ __('Quick Action') }}</div>
            </div>
            <div class="card-body quick-action-grid">
                @can('manage_work_orders')
                <a href="{{ url('/work-orders/create') }}" class="quick-btn blue">
                    <i class="fa-solid fa-file-circle-plus" style="font-size: 1.5rem;"></i>
                    {{ __('Tambah WO') }}
                </a>
                @endcan
                
                @can('manage_qc')
                <a href="{{ route('qc.index') }}" class="quick-btn green">
                    <i class="fa-solid fa-flask" style="font-size: 1.5rem;"></i>
                    {{ __('Input QC') }}
                </a>
                @endcan
                
                @can('manage_waste')
                <a href="{{ route('waste.index') }}" class="quick-btn orange">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.5rem;"></i>
                    {{ __('Catat Waste') }}
                </a>
                @endcan
                
                @can('view_laporan')
                <a href="{{ route('laporan.index') }}" class="quick-btn purple">
                    <i class="fa-solid fa-chart-pie" style="font-size: 1.5rem;"></i>
                    {{ __('Laporan') }}
                </a>
                @endcan
            </div>
        </div>
        @endcanany

        <!-- VSM & Efisiensi -->
        @can('view_vsm')
        <div class="card anim-slide-up delay-3">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-chart-pie"></i> {{ __('VSM & Efisiensi') }}</div>
                <a href="{{ route('vsm.index') }}" style="font-size:0.8rem; color:var(--primary);">{!! __('Lihat Detail &rarr;') !!}</a>
            </div>
            <div class="card-body" style="display:flex; flex-direction:column; gap:1rem;">
                <div style="display:flex; justify-content:space-between; font-size:0.85rem; font-weight:600;">
                    <span>Cycle Time (CT)</span>
                    <span>{{ $ct }} menit <span style="color:var(--success); margin-left:0.5rem;"><i class="fa-solid fa-arrow-down"></i> 8%</span></span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.85rem; font-weight:600;">
                    <span>Lead Time (PLT)</span>
                    <span>{{ $plt }} hari <span style="color:var(--danger); margin-left:0.5rem;"><i class="fa-solid fa-arrow-up"></i> 12%</span></span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.85rem; font-weight:600;">
                    <span>PCE</span>
                    <span>{{ $pce }}% <span style="color:var(--success); margin-left:0.5rem;"><i class="fa-solid fa-arrow-up"></i> 6%</span></span>
                </div>
            </div>
        </div>
        @endcan
    </div>
</div>

<script src="https://js.pusher.com/8.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.min.js"></script>
<script>
    window.Pusher = Pusher;
    Pusher.logToConsole = false; // Matikan log bawaan Pusher

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env("REVERB_APP_KEY") }}',
        wsHost: '{{ env("REVERB_HOST", "localhost") }}',
        wsPort: {{ env('REVERB_PORT', 8080) }},
        wssPort: {{ env('REVERB_PORT', 8080) }},
        cluster: 'mt1',
        forceTLS: false,
        encrypted: false,
        disableStats: true,
        enabledTransports: ['ws', 'wss'],
    });

    // Mencegah spam eror merah di console jika Reverb mati
    window.Echo.connector.pusher.connection.bind('state_change', function(states) {
        if (states.current === 'unavailable' || states.current === 'failed') {
            window.Echo.disconnect(); // Matikan paksa jika server reverb tidak merespons
            console.log('%c[Sistem] Mode Real-time nonaktif (Server Reverb mati).', 'color: #f59e0b; font-weight: bold;');
        }
    });

    // Perbarui dashboard secara instan hanya saat ada aksi (WebSockets)
    window.Echo.channel('produksi')
        .listen('ProductionUpdated', (e) => {
            $.ajax({
                url: window.location.href,
                success: function(response) {
                    var updatedHtml = $(response).find('.dashboard-grid').html();
                    $('.dashboard-grid').html(updatedHtml);
                }
            });
        });
</script>
@endsection
