<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SupplyOrder;

class SupplyOrderPolicy
{
    /**
     * ¿Puede ver la lista de órdenes de suministro?
     */
    public function viewAny(User $user): bool
    {
        return $user->isEmployee() || $user->isBoard();
    }

    /**
     * ¿Puede crear órdenes de suministro manual o auto?
     */
    public function create(User $user): bool
    {
        return $user->isEmployee() || $user->isBoard();
    }

    /**
     * ¿Puede marcar una orden de suministro como completada?
     */
    public function complete(User $user, SupplyOrder $supplyOrder): bool
    {
        return ($user->isEmployee() || $user->isBoard())
            && $supplyOrder->status === 'requested';
    }

    /**
     * ¿Puede eliminar una orden de suministro?
     */
    public function delete(User $user, SupplyOrder $supplyOrder): bool
    {
        return $user->isBoard()
            && $supplyOrder->status === 'requested';
    }
}