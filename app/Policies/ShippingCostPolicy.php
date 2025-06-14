<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ShippingCost;

class ShippingCostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isBoard();
    }
    public function create(User $user): bool
    {
        return $user->isBoard();
    }
    public function update(User $user): bool
    {
        return $user->isBoard();
    }
    public function delete(User $user): bool
    {
        return $user->isBoard();
    }
}