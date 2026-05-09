<?php

namespace App\Policies;

use App\Models\User;

class LoanPolicy
{
    public function approveInspection(User $user): bool
    {
        return $user->is_admin;
    }
}