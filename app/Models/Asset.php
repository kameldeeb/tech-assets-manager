<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Enums\AssetStatus;

class Asset extends Model
{
    protected $fillable = [
        'asset_type_id',
        'serial_number',
        'purchase_date',
        'status',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'status' => AssetStatus::class,
    ];

    public function assetType()
    {
        return $this->belongsTo(AssetType::class);
    }
    
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    public function scopeIdle($query)
    {
        return $query->whereDoesntHave('loans', function ($q) {
            $q->where(
                'borrowed_at',
                '>=',
                now()->subYear()
            );
        });
    }

    public function getDaysInStockAttribute()
    {
        $lastLoan = $this->loans()
            ->orderByDesc('borrowed_at')
            ->first();

        $referenceDate = optional($lastLoan)->returned_at ?? $this->purchase_date;

        return $referenceDate
            ? $referenceDate->diffInDays(now())
            : null;
    }
}
