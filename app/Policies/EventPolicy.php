<?php


namespace App\Policies;
use App\Models\Event;
use App\Models\User;


class EventPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }
    public function create(User $user)
    {
        return $user->isAdmin();
    }
    public function update(User $user, Event $event)
    {
        return $user->isAdmin();
    }
    public function delete(User $user, Event $event)
    {
        return $user->isAdmin();
    }
}
