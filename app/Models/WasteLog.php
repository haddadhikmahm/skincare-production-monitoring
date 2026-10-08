<?php

namespace App\Models;

use Database\Factories\WasteLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteLog extends Model
{
    /** @use HasFactory<WasteLogFactory> */
    use HasFactory;
    
    protected $guarded = [];

    public function workOrder() {
        return $this->belongsTo(WorkOrder::class);
    }
    public function workstation() {
        return $this->belongsTo(Workstation::class);
    }
    public function reporter() {
        return $this->belongsTo(User::class, 'logged_by');
    }
}
