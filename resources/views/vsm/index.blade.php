@extends('layouts.app')
@section('content')

<!-- Style Khusus VSM (Ultra Premium, No Scroll) -->
<style>
    /* Header Gradient & Animation */
    .vsm-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        border-radius: 16px;
        padding: 2rem 2.5rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.4);
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .vsm-header::after {
        content: ''; position: absolute; top: -50%; right: -10%; width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%);
        border-radius: 50%; animation: pulseLight 4s infinite alternate;
    }
    @keyframes pulseLight { from { transform: scale(1); opacity: 0.6; } to { transform: scale(1.2); opacity: 1; } }

    /* KPI Cards */
    .vsm-kpi-wrapper {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .vsm-kpi {
        background: white;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s;
    }
    .vsm-kpi:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
    }
    .vsm-kpi-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
    }
    .vsm-kpi-content h4 { font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 0.2rem 0; }
    .vsm-kpi-content .value { font-size: 1.6rem; font-weight: 800; color: #0f172a; line-height: 1; }
    .vsm-kpi-content .unit { font-size: 0.8rem; color: #94a3b8; font-weight: 600; }

    /* Map Canvas */
    .vsm-canvas {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        padding: 2.5rem 2rem;
        position: relative;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }
    
    /* VSM Nodes & Flow */
    .vsm-flow-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        width: 100%;
        margin-bottom: 3rem;
    }
    
    .vsm-node {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 130px;
        z-index: 2;
    }
    
    /* End Nodes (Supplier / Customer) */
    .vsm-node-end {
        background: #f8fafc;
        border: 2px solid #cbd5e1;
        border-radius: 12px;
        width: 100%;
        padding: 1rem 0;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }
    .vsm-node-end i { font-size: 1.5rem; color: #475569; margin-bottom: 0.5rem; }
    .vsm-node-end span { display: block; font-size: 0.8rem; font-weight: 700; color: #1e293b; text-transform: uppercase; }

    /* Process Data Boxes */
    .vsm-databox {
        width: 100%;
        background: white;
        border: 2px solid #3b82f6;
        border-radius: 8px;
        box-shadow: 0 6px 15px rgba(59,130,246,0.1);
        overflow: hidden;
    }
    .vsm-databox-header {
        background: #3b82f6;
        color: white;
        padding: 0.5rem;
        text-align: center;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .vsm-databox-body {
        padding: 0.5rem;
    }
    .vsm-data-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.7rem;
        padding: 0.2rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .vsm-data-row:last-child { border-bottom: none; }
    .vsm-data-label { color: #64748b; font-weight: 600; }
    .vsm-data-val { color: #0f172a; font-weight: 800; }

    /* Connectors / Arrows */
    .vsm-connector {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 0 0.5rem;
    }
    .vsm-arrow-body {
        width: 100%;
        height: 4px;
        background: #cbd5e1;
        position: relative;
    }
    .vsm-arrow-body::after {
        content: ''; position: absolute; right: -4px; top: -5px;
        border-top: 7px solid transparent; border-bottom: 7px solid transparent; border-left: 9px solid #cbd5e1;
    }
    .vsm-push-icon {
        position: absolute;
        top: -25px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 700;
        border: 1px solid #e2e8f0;
    }

    /* Timeline Section */
    .vsm-timeline-wrapper {
        display: flex;
        width: 100%;
        align-items: flex-end;
        position: relative;
        margin-top: 1rem;
    }
    .vsm-t-segment {
        display: flex;
        align-items: flex-end;
        position: relative;
    }
    
    /* NVA (Red Line, Top Level) */
    .vsm-t-nva {
        flex-grow: 1; /* Stretch across connectors */
        border-top: 3px solid #ef4444;
        border-right: 3px solid #ef4444;
        height: 20px;
        position: relative;
    }
    .vsm-t-nva span {
        position: absolute; top: -22px; left: 50%; transform: translateX(-50%);
        color: #ef4444; font-size: 0.7rem; font-weight: 800;
    }

    /* VA (Green Line, Bottom Level) */
    .vsm-t-va {
        width: 130px; /* Exact width of the node */
        border-top: 3px solid #10b981;
        border-right: 3px solid #10b981;
        height: 40px;
        position: relative;
        flex-shrink: 0;
    }
    .vsm-t-va span {
        position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%);
        color: #10b981; font-size: 0.7rem; font-weight: 800;
    }
    
    /* === PRINT OPTIMIZATION (EXPORT) === */
    @media print {
        @page { size: landscape A4; margin: 1cm; }
        
        body { 
            background: white !important; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
        }
        
        /* Sembunyikan elemen aplikasi yang tidak perlu dicetak */
        .sidebar, .top-header, .navbar, .mobile-overlay, nav { display: none !important; }
        .main-wrapper, .main-content { 
            margin: 0 !important; 
            padding: 0 !important; 
            width: 100% !important; 
            overflow: visible !important;
        }

        /* Sesuaikan jarak antar elemen agar tidak mepet */
        .vsm-header {
            padding: 1rem !important;
            margin-bottom: 1.5rem !important;
            border-radius: 8px !important;
            box-shadow: none !important;
            page-break-after: avoid;
        }
        .vsm-header button { display: none !important; } /* Sembunyikan tombol cetak */

        .vsm-kpi-wrapper {
            margin-bottom: 2rem !important;
            gap: 1.5rem !important;
            page-break-inside: avoid;
        }
        .vsm-kpi { 
            box-shadow: none !important; 
            border: 1px solid #cbd5e1 !important; 
            padding: 1rem !important;
        }

        /* Perbaikan kanvas utama agar tidak bertumpuk/mepet */
        .vsm-canvas { 
            box-shadow: none !important; 
            border: 2px solid #94a3b8 !important; 
            border-radius: 8px !important;
            padding: 2rem !important;
            page-break-inside: avoid;
            width: 100% !important;
            margin: 0 !important;
        }

        .vsm-flow-container {
            margin-bottom: 4rem !important;
        }
        
        .vsm-node {
            width: 140px !important;
            flex-shrink: 0 !important;
        }

        .vsm-connector {
            min-width: 50px !important;
        }

        /* Paksa warna agar tebal saat diprint */
        .vsm-node-end { border: 2px solid #475569 !important; background: #f8fafc !important; }
        .vsm-databox { border: 2px solid #2563eb !important; }
        .vsm-databox-header { background: #2563eb !important; color: white !important; }
        .vsm-arrow-body { background: #94a3b8 !important; }
        .vsm-arrow-body::after { border-left-color: #94a3b8 !important; }
        .vsm-t-nva { border-top-color: #dc2626 !important; border-right-color: #dc2626 !important; }
        .vsm-t-nva span { color: #dc2626 !important; font-size: 0.8rem !important; top: -25px !important; }
        .vsm-t-va { border-top-color: #059669 !important; border-right-color: #059669 !important; width: 140px !important; }
        .vsm-t-va span { color: #059669 !important; font-size: 0.8rem !important; bottom: 8px !important; }
    }
</style>

<!-- 1. Header Premium -->
<div class="vsm-header anim-slide-up">
    <div style="position: relative; z-index: 2;">
        <h1 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 0.2rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-map" style="color: #818cf8;"></i> Value Stream Mapping
        </h1>
        <p style="color: #cbd5e1; font-size: 0.9rem; margin: 0;">Analisis Rantai Nilai & Efisiensi Lintasan Produksi</p>
    </div>
    <div style="position: relative; z-index: 2;">
        <button class="btn" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 0.6rem 1.2rem; font-size: 0.85rem;" onclick="window.print()">
            <i class="fa-solid fa-print"></i>{{ __('Cetak Laporan') }}</button>
    </div>
</div>

<!-- 2. KPI Metrics Grid -->
<div class="vsm-kpi-wrapper">
    <!-- PCE -->
    <div class="vsm-kpi anim-slide-up delay-1">
        <div class="vsm-kpi-icon" style="background: #ecfdf5; color: #10b981;"><i class="fa-solid fa-gauge-high"></i></div>
        <div class="vsm-kpi-content">
            <h4>Efisiensi (PCE)</h4>
            <div class="value">{{ $pce }}<span class="unit">%</span></div>
        </div>
    </div>
    <!-- VA Time -->
    <div class="vsm-kpi anim-slide-up delay-1">
        <div class="vsm-kpi-icon" style="background: #eff6ff; color: #3b82f6;"><i class="fa-solid fa-clock"></i></div>
        <div class="vsm-kpi-content">
            <h4>Value Added (VA)</h4>
            <div class="value">{{ $totalVA }} <span class="unit">menit</span></div>
        </div>
    </div>
    <!-- NVA Time -->
    <div class="vsm-kpi anim-slide-up delay-2">
        <div class="vsm-kpi-icon" style="background: #fef2f2; color: #ef4444;"><i class="fa-solid fa-stopwatch"></i></div>
        <div class="vsm-kpi-content">
            <h4>Non-Value Added</h4>
            <div class="value">{{ $totalNVA }} <span class="unit">menit</span></div>
        </div>
    </div>
    <!-- Lead Time -->
    <div class="vsm-kpi anim-slide-up delay-2">
        <div class="vsm-kpi-icon" style="background: #f5f3ff; color: #8b5cf6;"><i class="fa-solid fa-route"></i></div>
        <div class="vsm-kpi-content">
            <h4>Total Lead Time</h4>
            <div class="value">{{ $totalLeadTime }} <span class="unit">menit</span></div>
        </div>
    </div>
</div>

<!-- 3. VSM Canvas -->
<div class="vsm-canvas anim-slide-up delay-3">
    
    <!-- Flow Container -->
    <div class="vsm-flow-container">
        
        <!-- SUPPLIER -->
        <div class="vsm-node">
            <div class="vsm-node-end">
                <i class="fa-solid fa-truck-ramp-box"></i>
                <span>Supplier</span>
            </div>
        </div>

        <!-- CONNECTOR -->
        <div class="vsm-connector">
            <div class="vsm-push-icon"><i class="fa-solid fa-truck"></i></div>
            <div class="vsm-arrow-body"></div>
        </div>

        <!-- WORKSTATIONS -->
        @foreach($workstations as $ws)
            <div class="vsm-node">
                <!-- Data Box Standar VSM -->
                <div class="vsm-databox">
                    <div class="vsm-databox-header" title="{{ $ws->name }}">{{ $ws->name }}</div>
                    <div class="vsm-databox-body">
                        <div class="vsm-data-row">
                            <span class="vsm-data-label">C/T</span>
                            <span class="vsm-data-val">{{ $ws->cycle_time }} m</span>
                        </div>
                        <div class="vsm-data-row">
                            <span class="vsm-data-label">Uptime</span>
                            <span class="vsm-data-val">{{ $ws->uptime }}%</span>
                        </div>
                        <div class="vsm-data-row">
                            <span class="vsm-data-label">Defect</span>
                            <span class="vsm-data-val">{{ $ws->defect_rate }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONNECTOR -->
            <div class="vsm-connector">
                <div class="vsm-push-icon"><i class="fa-solid fa-arrow-right-arrow-left"></i> Push</div>
                <div class="vsm-arrow-body"></div>
            </div>
        @endforeach

        <!-- CUSTOMER -->
        <div class="vsm-node">
            <div class="vsm-node-end" style="border-color: #10b981;">
                <i class="fa-solid fa-hand-holding-box" style="color: #10b981;"></i>
                <span>Customer</span>
            </div>
        </div>

    </div>

    <!-- Timeline Ladder -->
    <div class="vsm-timeline-wrapper">
        
        <!-- First NVA (Supplier to First WS) -->
        <div class="vsm-t-nva">
            <span>{{ round($totalNVA / (count($workstations) + 1), 1) }}m</span>
        </div>

        @foreach($workstations as $ws)
            <!-- VA for Workstation -->
            <div class="vsm-t-va">
                <span>{{ $ws->cycle_time }}m</span>
            </div>
            
            <!-- NVA (Between WS or WS to Customer) -->
            <div class="vsm-t-nva">
                <span>{{ round($totalNVA / (count($workstations) + 1), 1) }}m</span>
            </div>
        @endforeach

    </div>
</div>

@endsection
