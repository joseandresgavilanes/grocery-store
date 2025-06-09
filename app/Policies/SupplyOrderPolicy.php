<?php
// app/Policies/SupplyOrderPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\SupplyOrder;

class SupplyOrderPolicy
{
    /**
     * Listar supply orders: employee o board.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->type, ['employee', 'board']);
    }

    /**
     * Crear supply order (manual o auto): employee o board.
     */
    public function create(User $user): bool
    {
        return in_array($user->type, ['employee', 'board']);
    }

    /**
     * Completar supply order (status → completed + stock++): employee o board,
     * y sólo si status = requested.
     */
    public function complete(User $user, SupplyOrder $order): bool
    {
        return in_array($user->type, ['employee', 'board'])
            && $order->status === 'requested';
    }

    /**
     * Borrar supply order: employee o board,
     * y sólo si no está completed (o según tu regla).
     */
    public function delete(User $user, SupplyOrder $order): bool
    {
        return in_array($user->type, ['employee', 'board'])
            && $order->status !== 'completed';
    }
}