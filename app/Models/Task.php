<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'due_date' => 'datetime',
        'is_penalized' => 'boolean',
        'penalty_amount' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
