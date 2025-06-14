<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SupplyOrder;

class SupplyOrderPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->isEmployee() || $user->isBoard();
    }


    public function create(User $user): bool
    {
        return $user->isEmployee() || $user->isBoard();
    }


    public function complete(User $user, SupplyOrder $supplyOrder): bool
    {
        return ($user->isEmployee() || $user->isBoard())
            && $supplyOrder->status === 'requested';
    }


    public function delete(User $user, SupplyOrder $supplyOrder): bool
    {
        return $user->isBoard()
            && $supplyOrder->status === 'requested';
    }
}
