<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyIncome extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'income_date' => 'date'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
