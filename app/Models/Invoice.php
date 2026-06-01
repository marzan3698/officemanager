<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'employee_id',
        'client_name',
        'client_phone',
        'items',
        'total_amount',
        'status',
        'paid_at',
        'payment_ref',
        'proof_file'
    ];

    protected $casts = [
        'items' => 'array',
        'paid_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
