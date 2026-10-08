<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ProductionProcess;
use App\Models\WorkOrder;
use App\Models\Workstation;

class ProductionController extends Controller
{
    public function index() {
        $processes = ProductionProcess::with(['workOrder', 'workstation', 'operator'])->latest()->get();
        $workOrders = WorkOrder::whereIn('status', ['Pending', 'In Progress'])->get();
        $workstations = Workstation::all();
        return view('produksi.index', compact('processes', 'workOrders', 'workstations'));
    }
    
    private function triggerUpdateEvent() {
        try {
            event(new \App\Events\ProductionUpdated());
        } catch (\Exception $e) {
            // Ignore broadcast exception if Reverb/Pusher is offline
            \Illuminate\Support\Facades\Log::warning('Broadcast failed: ' . $e->getMessage());
        }
    }

    public function store(Request $request) {
        $request->validate(['work_order_id' => 'required', 'workstation_id' => 'required']);
        ProductionProcess::create([
            'work_order_id' => $request->work_order_id,
            'workstation_id' => $request->workstation_id,
            'start_time' => now(),
            'operator_id' => auth()->id(),
            'status' => 'In Progress'
        ]);
        WorkOrder::where('id', $request->work_order_id)->update(['status' => 'In Progress']);
        
        $this->triggerUpdateEvent();
        
        return back()->with('success', 'Proses produksi berhasil dimulai.');
    }
    
    public function update(Request $request, $id) {
        $process = ProductionProcess::findOrFail($id);
        $process->update([
            'finish_time' => now(),
            'status' => 'Completed'
        ]);
        
        $this->triggerUpdateEvent();
        
        return back()->with('success', 'Proses diselesaikan.');
    }
    
    public function destroy($id) {
        ProductionProcess::findOrFail($id)->delete();
        
        $this->triggerUpdateEvent();
        
        return back()->with('success', 'Log Produksi dihapus.');
    }
}
