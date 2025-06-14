<?php

namespace App\Policies;

use App\Models\User;

class SettingsPolicy
{
    public function view(User $user): bool
    {
        return $user->isBoard();
    }
    public function update(User $user): bool
    {
        return $user->isBoard();
    }
}