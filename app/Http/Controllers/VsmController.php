<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class VsmController extends Controller {
    public function index() {
        // Calculate total production time (Value Added) in minutes
        $processes = \App\Models\ProductionProcess::whereNotNull('finish_time')->get();
        $totalVA = 0;
        foreach($processes as $p) {
            $totalVA += \Carbon\Carbon::parse($p->start_time)->diffInMinutes(\Carbon\Carbon::parse($p->finish_time));
        }

        // Calculate total downtime (Non-Value Added) in minutes
        $totalNVA = \App\Models\WasteLog::sum('downtime_minutes');

        // For demo purposes if there's no data
        if ($totalVA == 0) $totalVA = 480; // 8 hours default VA
        if ($totalNVA == 0) $totalNVA = 45; // 45 mins default NVA
        
        $totalLeadTime = $totalVA + $totalNVA;
        
        // PCE (Process Cycle Efficiency)
        $pce = round(($totalVA / $totalLeadTime) * 100, 1);

        // Fetch Workstation data for table
        $workstations = \App\Models\Workstation::all()->map(function($ws) {
            // Mock calculations per workstation for the VSM table
            $ws->cycle_time = rand(10, 30);
            $ws->uptime = rand(85, 98);
            $ws->defect_rate = rand(1, 5) / 10;
            return $ws;
        });

        return view('vsm.index', compact('totalVA', 'totalNVA', 'totalLeadTime', 'pce', 'workstations'));
    }
}
