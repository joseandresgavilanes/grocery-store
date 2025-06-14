<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    /**
     * ¿Puede ver el inventario?
     */
    public function viewAny(User $user): bool
    {
        return $user->isEmployee() || $user->isBoard();
    }
}