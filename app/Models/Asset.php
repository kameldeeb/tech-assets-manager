<?php

namespace App\Models;

use App\Enums\AssetStatus;
use App\Enums\Condition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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

    public function currentHolder(): HasOneThrough
    {
        return $this->hasOneThrough(
            Employee::class,
            Loan::class,
            'asset_id',    // loans
            'id',          // employees
            'id',          // assets
            'employee_id'  // loans
        )->whereNull('loans.returned_at');
    }


    public function getLatestNoteAttribute(): string
    {
        return $this->inspections()->latest()->first()?->notes ?? 'No remarks';
    }

    public function getDaysInStockAttribute(): ?int
    {
        return $this->purchase_date
            ? $this->purchase_date->diffInDays(now())
            : null;
    }

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


    public function isAvailable(): bool
    {
        return $this->status === AssetStatus::AVAILABLE;
    }

    public function scopeIdle(Builder $query): Builder
    {
        return $query->whereDoesntHave('loans', function (Builder $query) {
            $query->where('borrowed_at', '>=', now()->subYear());
        });
    }
}
