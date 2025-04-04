<?php

namespace App\Policies;

use App\Models\Arrival;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArrivalPilicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Arrival $arrival): bool
    {
        return $user->id === $arrival->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 0 || $user->role === 1; // 従業員と管理者
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Arrival $arrival): bool
    {
        return $user->id === $arrival->user_id;
    }
}
