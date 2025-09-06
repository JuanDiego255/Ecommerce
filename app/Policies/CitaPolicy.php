<?php


namespace App\Policies;


use App\Models\Cita;
use App\Models\User;


class CitaPolicy
{
    public function manageCita(User $user, \App\Models\Cita $cita): bool
    {
        return $user->role === 'super_admin' || ($cita->barbero && $cita->barbero->user_id === $user->id);
    }


    public function superAdmin(User $user): bool
    {
        return $user->role === 'super_admin';
    }
}
