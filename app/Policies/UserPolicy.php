<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $auth): bool
    {
        return $auth->isBoard();
    }

    public function create(User $auth): bool
    {
        return $auth->isBoard();
    }

    public function update(User $auth, User $user): bool
    {
        return $auth->isBoard() && $auth->id !== $user->id;
    }

    public function delete(User $auth, User $user): bool
    {
        return $auth->isBoard() && $auth->id !== $user->id;
    }

    public function block(User $auth, User $user): bool
    {
        // Sólo board puede bloquear a otros (no a sí mismo)
        return $this->delete($auth, $user);
    }

    public function unblock(User $auth, User $user): bool
    {
        return $this->delete($auth, $user);
    }

    public function promote(User $auth, User $user): bool
    {
        // Sólo board y sólo sobre miembros regulares
        return $this->delete($auth, $user)
            && $user->type === 'member';
    }

    public function demote(User $auth, User $user): bool
    {
        // Sólo board y sólo sobre board distintos de sí mismo
        return $this->delete($auth, $user)
            && $user->type === 'board';
    }
}