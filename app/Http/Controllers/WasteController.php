<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\WasteLog;
use App\Models\WorkOrder;
use App\Models\Workstation;

class WasteController extends Controller
{
    public function index() {
        $wastes = WasteLog::with(['workOrder', 'workstation', 'reporter'])->latest()->get();
        $workOrders = WorkOrder::where('status', 'In Progress')->get();
        $workstations = Workstation::all();
        return view('waste.index', compact('wastes', 'workOrders', 'workstations'));
    }
    public function store(Request $request) {
        $request->validate(['work_order_id' => 'required', 'workstation_id' => 'required', 'reason' => 'required']);
        WasteLog::create([
            'work_order_id' => $request->work_order_id,
            'workstation_id' => $request->workstation_id,
            'defects_count' => $request->waste_quantity ?? 0,
            'downtime_minutes' => $request->downtime_minutes ?? 0,
            'downtime_reason' => $request->reason,
            'logged_by' => auth()->id()
        ]);
        return back()->with('success', 'Laporan Waste / Downtime tersimpan.');
    }
    public function destroy($id) {
        WasteLog::findOrFail($id)->delete();
        return back()->with('success', 'Log Waste dihapus.');
    }
}
