<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkOrder;
use App\Models\Workstation;

class DashboardController extends Controller
{
    public function index()
    {
        // Dynamic stats
        $workOrders = WorkOrder::latest()->take(5)->get();
        $totalWorkOrders = WorkOrder::count();
        $pendingWO = WorkOrder::where('status', 'Pending')->count();
        $inProgressWO = WorkOrder::where('status', 'In Progress')->count();
        $completedWO = WorkOrder::where('status', 'Completed')->count();
        
        // Workstations for workflow
        $workstations = Workstation::all();
        
        // Dummy logic to dynamically simulate real-time metrics based on current DB state
        // In real app, this queries ProductionProcesses & WasteLogs
        $ct = number_format(12.5 - ($completedWO * 0.1), 1);
        $plt = number_format(4.8 - ($completedWO * 0.05), 1);
        $pce = number_format(78.3 + ($completedWO * 0.2), 1);

        return view('dashboard', compact(
            'workOrders', 'totalWorkOrders', 'pendingWO', 'inProgressWO', 'completedWO', 
            'workstations', 'ct', 'plt', 'pce'
        ));
    }
}
