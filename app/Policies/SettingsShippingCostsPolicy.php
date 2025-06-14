<?php
// app/Policies/SettingsShippingCostsPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\SettingsShippingCosts;

class SettingsShippingCostsPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isBoard();
    }

    public function view(User $user, SettingsShippingCosts $cost): bool
    {
        return $user->isBoard();
    }

    public function create(User $user): bool
    {
        return $user->isBoard();
    }

    public function update(User $user, SettingsShippingCosts $cost): bool
    {
        return $user->isBoard();
    }

    public function delete(User $user, SettingsShippingCosts $cost): bool
    {
        return $user->isBoard();
    }
}