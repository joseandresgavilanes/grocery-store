<?php


namespace App\Policies;

use App\Models\User;
use App\Models\StockAdjustment;

class StockAdjustmentPolicy
{

    public function create(User $user): bool
    {
        return $user->isEmployee() || $user->isBoard();
    }


    public function viewAny(User $user): bool
    {
        return $user->isEmployee() || $user->isBoard();
    }
}
