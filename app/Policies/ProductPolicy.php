<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->isEmployee() || $user->isBoard();
    }

    public function view(User $user, Product $product): bool
    {
        return $user->isBoard();
    }
    public function create(User $user): bool
    {
        return $user->isBoard();
    }
    public function update(User $user, Product $product): bool
    {
        return $user->isBoard();
    }
    public function delete(User $user, Product $product): bool
    {

        return $user->isBoard();
    }
}
