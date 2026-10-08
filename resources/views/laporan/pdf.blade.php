<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Laporan Produksi Terpadu') }}</title>
    <style>
        @page { margin: 40px 50px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #334155; margin: 0; line-height: 1.4; }
        
        /* Header Box */
        .header-box { width: 100%; border-bottom: 3px solid #1d4ed8; padding-bottom: 20px; margin-bottom: 25px; }
        .header-box table { width: 100%; border: none; }
        .header-box td { border: none; padding: 0; }
        .company-name { font-size: 22px; font-weight: bold; color: #0f172a; margin: 0 0 5px 0; }
        .company-sub { font-size: 11px; color: #64748b; margin: 0; }
        
        .report-title { font-size: 18px; font-weight: bold; color: #1d4ed8; text-transform: uppercase; letter-spacing: 1px; margin: 0; text-align: right; }
        .report-meta { font-size: 10px; color: #64748b; text-align: right; margin-top: 5px; }
        
        /* Filter Info */
        .filter-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 12px 15px; margin-bottom: 25px; }
        .filter-box table { width: 100%; border: none; margin: 0; }
        .filter-box td { border: none; padding: 3px 0; font-size: 10px; color: #475569; }
        .filter-label { font-weight: bold; color: #1e293b; width: 120px; }
        
        /* Main Table */
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .data-table th, .data-table td { border: 1px solid #cbd5e1; padding: 10px 12px; text-align: left; vertical-align: top; }
        .data-table th { background-color: #1e293b; color: #ffffff; font-weight: bold; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .data-table tr:nth-child(even) { background-color: #f8fafc; }
        .data-table td { color: #1e293b; font-size: 10px; }
        
        /* Badges/Status */
        .status { font-weight: bold; font-size: 9px; text-transform: uppercase; padding: 3px 6px; border-radius: 3px; display: inline-block; }
        .status-completed { color: #15803d; }
        .status-progress { color: #b45309; }
        
        /* Footer */
        .footer { width: 100%; margin-top: 40px; }
        .signature-box { float: right; width: 250px; text-align: center; }
        .signature-title { font-weight: bold; margin-bottom: 60px; font-size: 11px; color: #1e293b; }
        .signature-name { font-weight: bold; text-decoration: underline; color: #1e293b; font-size: 12px; margin-bottom: 3px; }
        .signature-role { color: #64748b; font-size: 10px; }
        
        .page-break { page-break-after: always; }
        .clearfix::after { content: ""; clear: both; display: table; }
        
        .system-footer { position: absolute; bottom: -10px; left: 0; right: 0; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header-box">
        <table>
            <tr>
                <td style="width: 50%;">
                    <p class="company-name">PT. SINAR ALFA OMEGA</p>
                    <p class="company-sub">Sistem Informasi Manajemen Produksi Maklon Kosmetik<br>Quality Production, Better Tomorrow</p>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <p class="report-title">{{ __('LAPORAN PRODUKSI TERPADU') }}</p>
                    <p class="report-meta">
                        Dicetak pada: <strong>{{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</strong><br>
                        Sistem Generate ID: <strong>RPT-{{ date('Ymd-His') }}</strong>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="filter-box">
        <table>
            <tr>
                <td class="filter-label">Periode Waktu</td>
                <td>: {{ $request->filled('date_start') ? \Carbon\Carbon::parse($request->date_start)->format('d M Y') : 'Awal' }} &nbsp;&mdash;&nbsp; {{ $request->filled('date_end') ? \Carbon\Carbon::parse($request->date_end)->format('d M Y') : 'Sekarang' }}</td>
                <td class="filter-label">Filter Status</td>
                <td>: {{ $request->filled('status') ? ($request->status == 'All' ? 'Semua Status' : ($request->status == 'Completed' ? 'Selesai' : 'Sedang Berjalan')) : 'Semua Status' }}</td>
            </tr>
            <tr>
                <td class="filter-label">Dicetak Oleh</td>
                <td>: {{ Auth::user()->name }}</td>
                <td class="filter-label">Hak Akses / Role</td>
                <td>: {{ Auth::user()->roles->pluck('name')->first() ?? 'User' }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">ID</th>
                <th style="width: 15%;">Nomor SPK</th>
                <th style="width: 15%;">Workstation</th>
                <th style="width: 15%;">{{ __('Operator') }}</th>
                <th style="width: 17%;">Waktu Mulai</th>
                <th style="width: 17%;">Waktu Selesai</th>
                <th style="width: 13%;">{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($processes as $p)
            <tr>
                <td>#{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td><strong>{{ $p->workOrder ? $p->workOrder->order_number : '-' }}</strong></td>
                <td>{{ $p->workstation ? $p->workstation->name : '-' }}</td>
                <td>{{ $p->operator ? $p->operator->name : '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->start_time)->format('d/m/Y H:i') }}</td>
                <td>{{ $p->finish_time ? \Carbon\Carbon::parse($p->finish_time)->format('d/m/Y H:i') : '-' }}</td>
                <td>
                    @if($p->status == 'Completed')
                        <span class="status status-completed">{{ __('Selesai') }}</span>
                    @else
                        <span class="status status-progress">Berjalan</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">Tidak ada data yang ditemukan untuk periode/filter ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer clearfix">
        <div class="signature-box">
            <p class="signature-title">Mengetahui & Mengesahkan,</p>
            <p class="signature-name">{{ Auth::user()->name }}</p>
            <p class="signature-role">{{ Auth::user()->roles->pluck('name')->first() ?? 'Authorized Signatory' }}</p>
        </div>
    </div>
    
    <div class="system-footer">
        Dicetak secara otomatis dari Sistem Monitoring Produksi Skincare &copy; {{ date('Y') }} PT. Sinar Alfa Omega
    </div>
</body>
</html>
