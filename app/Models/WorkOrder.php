<?php

namespace App\Models;

use Database\Factories\WorkOrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    /** @use HasFactory<WorkOrderFactory> */
    use HasFactory;
    
    protected $guarded = [];
}
