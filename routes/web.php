<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\QcController;
use App\Http\Controllers\WasteController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('can:view_dashboard');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Feature Routes
    Route::resource('work-orders', WorkOrderController::class)->middleware('can:manage_work_orders');
    Route::resource('produksi', ProductionController::class)->middleware('can:manage_produksi');
    Route::resource('qc', QcController::class)->middleware('can:manage_qc');
    Route::resource('waste', WasteController::class)->middleware('can:manage_waste');
    
    Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    
    Route::resource('vsm', \App\Http\Controllers\VsmController::class)->middleware('can:view_vsm');
    
    Route::middleware('can:view_laporan')->group(function () {
        Route::get('laporan/excel', [\App\Http\Controllers\LaporanController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('laporan/pdf', [\App\Http\Controllers\LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::resource('laporan', \App\Http\Controllers\LaporanController::class);
    });

    Route::resource('users', \App\Http\Controllers\UserController::class)->middleware('can:manage_users');
    
    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

    Route::get('/api/notifications', function() {
        $user = auth()->user();
        $wastes = collect();
        $qcs = collect();

        if ($user->can('manage_waste') || $user->can('view_laporan')) {
            $wastes = \App\Models\WasteLog::with('workstation')->latest()->take(3)->get()->map(function($w) {
                return (object)[
                    'type' => 'warning',
                    'icon' => 'fa-triangle-exclamation',
                    'title' => 'Waste: ' . ($w->workstation->name ?? 'Stasiun'),
                    'desc' => $w->reason ?? 'Kendala tak terduga pada mesin/proses',
                    'time' => $w->created_at->diffForHumans(),
                    'timestamp' => $w->created_at->timestamp,
                    'link' => route('waste.index')
                ];
            });
        }
        
        if ($user->can('manage_qc') || $user->can('view_laporan')) {
            $qcs = \App\Models\QcVerification::with('workOrder')->latest()->take(3)->get()->map(function($q) {
                return (object)[
                    'type' => $q->status == 'Lulus' ? 'success' : 'danger',
                    'icon' => $q->status == 'Lulus' ? 'fa-check' : 'fa-xmark',
                    'title' => 'QC ' . $q->status,
                    'desc' => 'WO: ' . ($q->workOrder->order_number ?? '-'),
                    'time' => $q->created_at->diffForHumans(),
                    'timestamp' => $q->created_at->timestamp,
                    'link' => route('qc.index')
                ];
            });
        }
        
        $all = $wastes->concat($qcs)->sortByDesc('timestamp')->take(5)->values();
        return response()->json(['notifications' => $all, 'count' => $all->count()]);
    })->name('api.notifications');

    Route::get('/api/produksi/data', function() {
        $processes = \App\Models\ProductionProcess::with(['workOrder', 'workstation'])->latest()->get();
        $data = $processes->map(function($p) {
            $start = \Carbon\Carbon::parse($p->start_time);
            $finish = $p->finish_time ? \Carbon\Carbon::parse($p->finish_time) : null;
            
            $durasi = '-';
            if ($finish) {
                $diffSec = $start->diffInSeconds($finish);
                if ($diffSec < 60) {
                    $durasi = $diffSec . ' detik';
                } elseif ($diffSec < 3600) {
                    $m = floor($diffSec / 60);
                    $s = $diffSec % 60;
                    $durasi = $s > 0 ? "{$m}m {$s}d" : "{$m} menit";
                } else {
                    $h = floor($diffSec / 3600);
                    $m = floor(($diffSec % 3600) / 60);
                    $durasi = $m > 0 ? "{$h}j {$m}m" : "{$h} jam";
                }
            }

            $durasiHtml = $finish 
                ? '<span style="font-weight: 600; color: #475569; font-size: 0.85rem;">' . $durasi . '</span>'
                : '<span style="color: #22c55e; font-weight: 600; font-size: 0.8rem; display: flex; align-items: center; gap: 0.3rem;"><span style="width: 6px; height: 6px; background: #22c55e; border-radius: 50%; animation: pulse 1.5s infinite;"></span> LIVE</span>';
                
            $statusLabel = $p->status == 'Completed' ? __('Completed') : __('In Progress');
            $statusClass = $p->status == 'Completed' ? 'badge-berjalan' : 'badge-selesai';
            $statusHtml = '<span class="flow-badge ' . $statusClass . '">' . $statusLabel . '</span>';
            
            $actionHtml = '<div class="action-group" style="justify-content: center;">';
            if ($p->status == 'In Progress') {
                $actionHtml .= '<form action="'.route('produksi.update', $p->id).'" method="POST" style="display: inline;">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit" class="btn-action btn-action-done" title="'.__('Selesaikan Proses').'">
                        <i class="fa-solid fa-flag-checkered"></i> '.__('Completed').'
                    </button>
                </form>';
            } else {
                $actionHtml .= '<span style="color: #94a3b8; font-size: 0.78rem; padding: 0.35rem 0.6rem; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">
                    <i class="fa-solid fa-check" style="color: #22c55e;"></i> '.__('Completed').'
                </span>';
            }
            $actionHtml .= ' <form action="'.route('produksi.destroy', $p->id).'" method="POST" class="form-delete" data-message="'.__('Hapus log proses ini?').'" style="display: inline;">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn-action btn-action-delete btn-delete-trigger" title="'.__('Hapus Log').'">
                        <i class="fa-solid fa-trash"></i> '.__('Hapus').'
                    </button>
                </form></div>';

            $runningText = __('Sedang berjalan...');
            $finishHtml = $finish 
                ? '<div style="font-size: 0.85rem;"><div style="font-weight: 600; color: #1e293b;">' . $finish->format('H:i:s') . '</div><div style="font-size: 0.73rem; color: #94a3b8;">' . $finish->format('d/m/Y') . '</div></div>' 
                : '<span style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">'.$runningText.'</span>';

            return [
                '<span style="font-family: \'Courier New\', monospace; font-weight: 700; font-size: 0.83rem; color: #0f172a; background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 5px;">' . ($p->workOrder->order_number ?? 'N/A') . '</span>',
                '<span style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-screwdriver-wrench" style="color: #94a3b8; font-size: 0.75rem;"></i><span style="font-weight: 500;">' . ($p->workstation->name ?? 'N/A') . '</span></span>',
                '<div style="font-size: 0.85rem;"><div style="font-weight: 600; color: #1e293b;">' . $start->format('H:i:s') . '</div><div style="font-size: 0.73rem; color: #94a3b8;">' . $start->format('d/m/Y') . '</div></div>',
                $finishHtml,
                $durasiHtml,
                $statusHtml,
                $actionHtml
            ];
        });
        
        return response()->json(['data' => $data]);
    })->name('api.produksi.data');
});
