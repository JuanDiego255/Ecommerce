<?php


namespace App\Policies;
use App\Models\Registration;
use App\Models\User;


class RegistrationPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }
    public function view(User $user, Registration $reg)
    {
        return $user->isAdmin();
    }
    public function update(User $user, Registration $reg)
    {
        return $user->isAdmin();
    }
}
