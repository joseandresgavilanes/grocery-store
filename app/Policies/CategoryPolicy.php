<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isBoard();
    }
    public function view(User $user, Category $category): bool
    {
        return $user->isBoard();
    }
    public function create(User $user): bool
    {
        return $user->isBoard();
    }
    public function update(User $user, Category $category): bool
    {
        return $user->isBoard();
    }
    public function delete(User $user, Category $category): bool
    {

        return $user->isBoard();
    }
}
