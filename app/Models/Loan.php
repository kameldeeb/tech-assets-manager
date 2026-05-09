<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
    'employee_id',
    'asset_id',
    'borrowed_at',
    'returned_at',
    'condition_at_checkout',
    'condition_at_return',
];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
