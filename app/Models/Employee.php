<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function scopeIntenseUsers(Builder $query): Builder
    {
        return $query->whereHas(
            'loans',
            function (Builder $query) {
                $query->where('borrowed_at', '>=', now()->subMonths(6));
            },
            '>',
            3
        );
    }
}
