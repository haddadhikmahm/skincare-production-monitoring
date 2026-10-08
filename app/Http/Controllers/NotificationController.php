<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WasteLog;
use App\Models\QcVerification;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $wastes = WasteLog::with('workstation')->latest()->take(100)->get()->map(function($w) {
            return (object)[
                'type' => 'warning',
                'icon' => 'fa-triangle-exclamation',
                'title' => 'Insiden Waste di ' . ($w->workstation->name ?? 'Stasiun'),
                'desc' => $w->reason ?? 'Kendala tak terduga pada mesin/proses',
                'time_human' => $w->created_at->diffForHumans(),
                'timestamp' => $w->created_at->timestamp,
                'date' => $w->created_at->format('d M Y H:i'),
                'raw_created_at' => $w->created_at,
                'link' => route('waste.index')
            ];
        });
        
        $qcs = QcVerification::with('workOrder')->latest()->take(100)->get()->map(function($q) {
            return (object)[
                'type' => $q->status == 'Lulus' ? 'success' : 'danger',
                'icon' => $q->status == 'Lulus' ? 'fa-check' : 'fa-xmark',
                'title' => 'QC ' . $q->status,
                'desc' => 'WO: ' . ($q->workOrder->order_number ?? '-'),
                'time_human' => $q->created_at->diffForHumans(),
                'timestamp' => $q->created_at->timestamp,
                'date' => $q->created_at->format('d M Y H:i'),
                'raw_created_at' => $q->created_at,
                'link' => route('qc.index')
            ];
        });
        
        // Combine and sort descending by timestamp
        $notifications = $wastes->concat($qcs)->sortByDesc('timestamp')->values();
        
        return view('notifications.index', compact('notifications'));
    }
}
