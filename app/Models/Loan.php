<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Enums\Condition;

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
    protected $casts = [
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
        'condition_at_checkout' => Condition::class,
        'condition_at_return' => Condition::class,
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
