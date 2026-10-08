<?php

namespace App\Models;

use Database\Factories\ProductionProcessFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionProcess extends Model
{
    /** @use HasFactory<ProductionProcessFactory> */
    use HasFactory;

    protected $guarded = [];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function workstation()
    {
        return $this->belongsTo(Workstation::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
