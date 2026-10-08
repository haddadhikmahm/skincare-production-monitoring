<?php
namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    public function index() {
        $workOrders = WorkOrder::latest()->get();
        return view('work_orders.index', compact('workOrders'));
    }
    public function create() {
        return view('work_orders.create');
    }
    public function store(Request $request) {
        $request->validate([
            'order_number' => 'required|unique:work_orders',
            'product_name' => 'required',
            'quantity' => 'required|numeric',
            'unit' => 'required|in:kg,pcs',
        ]);
        WorkOrder::create([
            'order_number' => $request->order_number,
            'product_name' => $request->product_name,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'status' => 'Pending',
            'created_by' => auth()->id(),
        ]);
        return redirect()->route('work-orders.index')->with('success', 'Work Order berhasil ditambahkan!');
    }
    public function edit($id) {
        $workOrder = WorkOrder::findOrFail($id);
        return view('work_orders.edit', compact('workOrder'));
    }
    public function update(Request $request, $id) {
        $workOrder = WorkOrder::findOrFail($id);
        $request->validate([
            'order_number' => 'required|unique:work_orders,order_number,'.$workOrder->id,
            'product_name' => 'required',
            'quantity' => 'required|numeric',
            'unit' => 'required|in:kg,pcs',
            'status' => 'required|in:Pending,In Progress,Completed,On Hold',
        ]);
        $workOrder->update([
            'order_number' => $request->order_number,
            'product_name' => $request->product_name,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'status' => $request->status,
        ]);
        return redirect()->route('work-orders.index')->with('success', 'Work Order berhasil diperbarui!');
    }
    public function destroy($id) {
        WorkOrder::findOrFail($id)->delete();
        return back()->with('success', 'Work Order berhasil dihapus!');
    }
}
