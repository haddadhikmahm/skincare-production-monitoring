@extends('layouts.app')
@section('content')

{{-- Page Header --}}
<div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.35rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.6rem;">
            <span style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0;">
                <i class="fa-solid fa-gear"></i>
            </span>
            {{ __('Monitor Proses Produksi') }}
        </h1>
        <p style="color: #64748b; font-size: 0.85rem; margin-top: 0.35rem; margin-left: 3rem;">{{ __('Catat dan pantau proses produksi secara real-time') }}</p>
    </div>
</div>

{{-- Form Mulai Proses --}}
<div class="card anim-slide-up" style="margin-bottom: 1.5rem; overflow: hidden;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-circle-play" style="color: #3b82f6; margin-right: 0.4rem;"></i>
            {{ __('Mulai Proses Baru — Digital Timestamp') }}
        </div>
    </div>
    <div class="card-body" style="padding: 1.5rem;">
        <form action="{{ route('produksi.store') }}" method="POST">
            @csrf
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
                <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                    <label><i class="fa-solid fa-file-invoice" style="color: #94a3b8; margin-right: 0.3rem;"></i> Work Order</label>
                    <select name="work_order_id" class="form-control" required>
                        <option value="">-- Pilih Work Order --</option>
                        @foreach($workOrders as $wo)
                            <option value="{{ $wo->id }}">{{ $wo->order_number }} — {{ $wo->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                    <label><i class="fa-solid fa-industry" style="color: #94a3b8; margin-right: 0.3rem;"></i> {{ __('Stasiun Kerja') }}</label>
                    <select name="workstation_id" class="form-control" required>
                        <option value="">-- Pilih Stasiun --</option>
                        @foreach($workstations as $ws)
                            <option value="{{ $ws->id }}">{{ $ws->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 0;">
                    <button type="submit" class="btn btn-primary" style="height: 44px; padding: 0 1.5rem; font-weight: 600;">
                        <i class="fa-solid fa-play"></i>{{ __('Mulai Produksi') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Log Proses Produksi --}}
<div class="card anim-slide-up delay-1" style="overflow: hidden;">
    <div class="card-header">
        <div style="display: flex; align-items: center; gap: 0.6rem;">
            <div style="width: 8px; height: 8px; background: #3b82f6; border-radius: 50%; animation: pulse 2s infinite;"></div>
            <span class="card-title">{{ __('Log Proses Produksi — Real-time') }}</span>
        </div>
        <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">{{ $processes->count() ?? 0 }} entri tercatat</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table id="table-produksi">
                <thead>
                    <tr>
                        <th>Work Order</th>
                        <th>{{ __('Stasiun Kerja') }}</th>
                        <th>Waktu Mulai</th>
                        <th>Waktu Selesai</th>
                        <th>{{ __('Durasi') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th style="text-align: center;">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table content will be loaded via AJAX realtime -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.pusher.com/8.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.min.js"></script>

<script>
    $(document).ready(function() {
        // Hancurkan DataTable bawaan dari app.blade.php jika ada
        if ($.fn.DataTable.isDataTable('#table-produksi')) {
            $('#table-produksi').DataTable().destroy();
        }
        
        // Inisialisasi ulang dengan AJAX
        var table = $('#table-produksi').DataTable({
            "dom": '<"top"lf>rt<"bottom"ip><"clear">',
            "language": {
                "search": '<i class="fa-solid fa-magnifying-glass"></i>',
                "searchPlaceholder": "{{ __('Cari data...') }}",
                "lengthMenu": "{{ __('Tampilkan _MENU_ baris') }}",
                "info": "{!! __('Menampilkan _START_–_END_ dari <strong>_TOTAL_</strong> data') !!}",
                "infoEmpty": "{{ __('Tidak ada data tersedia') }}",
                "infoFiltered": "{{ __('(difilter dari _MAX_ total)') }}",
                "emptyTable": '<div class="table-empty" style="text-align:center; padding: 3.5rem 2rem; color: #94a3b8;"><i class="fa-solid fa-folder-open" style="font-size: 2.5rem; opacity: 0.5; margin-bottom: 1rem; display: block;"></i><strong>{{ __("Belum ada data") }}</strong><p style="font-size: 0.9rem; margin-top: 0.5rem;">{{ __("Data belum tersedia untuk saat ini.") }}</p></div>',
                "zeroRecords": '<div style="text-align:center; padding: 2rem; color: #94a3b8;"><i class="fa-solid fa-magnifying-glass" style="font-size: 1.5rem; opacity: 0.4; display: block; margin-bottom: 0.5rem;"></i>{{ __("Data tidak ditemukan") }}</div>',
                "paginate": {
                    "first": '<i class="fa-solid fa-angles-left"></i>',
                    "last": '<i class="fa-solid fa-angles-right"></i>',
                    "next": '<i class="fa-solid fa-angle-right"></i>',
                    "previous": '<i class="fa-solid fa-angle-left"></i>'
                }
            },
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            "autoWidth": false,
            "ajax": "{{ route('api.produksi.data') }}",
            "order": [], // Pertahankan urutan dari server (terbaru di atas)
            "deferRender": true,
            "createdRow": function(row, data, dataIndex) {
                // Ensure tooltips or other DOM elements in the new row are formatted properly if needed
            }
        });

        // Setup Laravel Echo via CDN
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
            }
        });

        // Listen for Real-Time Production Updates via WebSockets!
        window.Echo.channel('produksi')
            .listen('ProductionUpdated', (e) => {
                // Hanya update tabel (tanpa mengubah halaman pengguna) saat ada event real-time
                table.ajax.reload(null, false);
            });
    });
</script>
@endpush
