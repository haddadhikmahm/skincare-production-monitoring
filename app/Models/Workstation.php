<?php

namespace App\Models;

use Database\Factories\WorkstationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workstation extends Model
{
    /** @use HasFactory<WorkstationFactory> */
    use HasFactory;

    protected $guarded = [];
}
