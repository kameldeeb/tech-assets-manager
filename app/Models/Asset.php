<?php

namespace App\Models;

use App\Enums\AssetStatus;
use App\Enums\Condition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'asset_type_id',
        'serial_number',
        'purchase_date',
        'status',
        'condition',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'status' => AssetStatus::class,
        'condition' => Condition::class,
    ];

    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }

    public function scopeIdle(Builder $query): Builder
    {
        return $query->whereDoesntHave('loans', function (Builder $query) {
            $query->where('borrowed_at', '>=', now()->subYear());
        });
    }

    public function isAvailable(): bool
    {
        return $this->status === AssetStatus::AVAILABLE;
    }

    public function getDaysInStockAttribute(): ?int
    {
        return $this->purchase_date
            ? $this->purchase_date->diffInDays(now())
            : null;
    }

    /**
     * Convert days_in_stock into a human-readable format (Years, Months, Days).
     * This can be used globally across all reports.
     */
    public function getIdleDurationAttribute(): array
    {
        $days = $this->days_in_stock;

        if (! $days) {
            return ['d' => 0];
        }

        return [
            'y' => floor($days / 365),
            'm' => floor(($days % 365) / 30),
            'd' => ($days % 365) % 30,
        ];
    }
}
