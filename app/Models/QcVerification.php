<?php

namespace App\Models;

use Database\Factories\QcVerificationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcVerification extends Model
{
    /** @use HasFactory<QcVerificationFactory> */
    use HasFactory;

    protected $guarded = [];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
