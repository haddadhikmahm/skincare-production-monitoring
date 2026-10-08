<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\QcVerification;
use App\Models\WorkOrder;

class QcController extends Controller
{
    public function index() {
        $qcs = QcVerification::with(['workOrder', 'verifiedBy'])->latest()->get();
        $workOrders = WorkOrder::where('status', 'In Progress')->get();
        return view('qc.index', compact('qcs', 'workOrders'));
    }
    public function store(Request $request) {
        $request->validate(['work_order_id' => 'required', 'status' => 'required']);
        QcVerification::create([
            'work_order_id' => $request->work_order_id,
            'verified_at' => now(),
            'status' => $request->status,
            'notes' => $request->notes,
            'verified_by' => auth()->id()
        ]);
        if($request->status == 'Lulus') {
            WorkOrder::where('id', $request->work_order_id)->update(['status' => 'Completed']);
        }
        return back()->with('success', 'Hasil QC berhasil dicatat.');
    }
    public function destroy($id) {
        QcVerification::findOrFail($id)->delete();
        return back()->with('success', 'Data QC dihapus.');
    }
}
