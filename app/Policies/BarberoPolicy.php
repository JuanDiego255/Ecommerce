<?php


namespace App\Policies;

use App\Models\Barbero;
use App\Models\User;


class BarberoPolicy
{
    public function viewSchedule(User $user, Barbero $bar): bool
    {
        return $user->role === 'super_admin' || ($bar->user_id && $bar->user_id === $user->id);
    }
}
