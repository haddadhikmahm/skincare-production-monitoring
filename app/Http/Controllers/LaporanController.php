<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class LaporanController extends Controller {
    public function index(Request $request) {
        $processes = $this->getReportData($request);
        return view('laporan.index', compact('processes'));
    }

    public function exportExcel(Request $request) {
        $processes = $this->getReportData($request);
        
        $filename = "laporan_produksi_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'SPK', 'Workstation', 'Operator', 'Mulai', 'Selesai', 'Status'];

        $callback = function() use($processes, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($processes as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->workOrder ? $p->workOrder->order_number : '-',
                    $p->workstation ? $p->workstation->name : '-',
                    $p->operator ? $p->operator->name : '-',
                    $p->start_time,
                    $p->finish_time,
                    $p->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request) {
        $processes = $this->getReportData($request);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf', compact('processes', 'request'));
        return $pdf->download('laporan_produksi_' . date('Ymd_His') . '.pdf');
    }

    private function getReportData(Request $request) {
        $query = \App\Models\ProductionProcess::with(['workOrder', 'workstation', 'operator'])->latest();
        $user = auth()->user();
        
        // If not admin/supervisor, restrict to own data
        if (!$user->hasRole('admin') && !$user->hasRole('supervisor')) {
            $query->where('operator_id', $user->id);
        }
        
        // Filter by Date Start
        if ($request->filled('date_start')) {
            $query->whereDate('start_time', '>=', $request->date_start);
        }
        
        // Filter by Date End
        if ($request->filled('date_end')) {
            $query->whereDate('start_time', '<=', $request->date_end);
        }
        
        // Filter by Status
        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }
        
        return $query->get();
    }
}
