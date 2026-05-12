<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough; // أضفنا هذا السطر

class Employee extends Model
{
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function branch(): HasOneThrough
    {
        return $this->hasOneThrough(
            Branch::class, 
            Department::class, 
            'id',          // foreign key on departments table
            'id',          // foreign key on branches table
            'department_id', // local key on employees table
            'branch_id'    // local key on departments table
        );
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
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
