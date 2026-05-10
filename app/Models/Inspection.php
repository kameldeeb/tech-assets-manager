<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = [
        'asset_id',
        'loan_id',
        'inspected_by',
        'result',
        'notes',
        'inspected_at',
        'verified_condition',
        'new_status',
        'completed_at',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }
}
