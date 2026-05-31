<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
