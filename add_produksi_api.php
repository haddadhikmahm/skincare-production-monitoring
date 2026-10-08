<?php

$webRouteFile = __DIR__ . '/routes/web.php';
$content = file_get_contents($webRouteFile);

$apiRoute = <<<'EOD'

    Route::get('/api/produksi/data', function() {
        $processes = \App\Models\ProductionProcess::with(['workOrder', 'workstation'])->latest()->get();
        $data = $processes->map(function($p) {
            $start = \Carbon\Carbon::parse($p->start_time);
            $finish = $p->finish_time ? \Carbon\Carbon::parse($p->finish_time) : null;
            
            $durasi = '-';
            if ($finish) {
                $diff = $start->diffInMinutes($finish);
                $durasi = $diff >= 60 ? floor($diff/60).'j '.($diff%60).'m' : $diff . ' ' . __('menit');
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
EOD;

if (strpos($content, '/api/produksi/data') === false) {
    // Insert before the last closing group "});"
    $pos = strrpos($content, '});');
    if ($pos !== false) {
        $content = substr_replace($content, $apiRoute . "\n", $pos, 0);
        file_put_contents($webRouteFile, $content);
        echo "routes/web.php updated.\n";
    }
}
