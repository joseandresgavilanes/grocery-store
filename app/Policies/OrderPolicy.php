<?php
// app/Policies/OrderPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    /**
     * Ver la lista de pendientes: employee o board.
     */
    public function pending(User $user): bool
    {
        return in_array($user->type, ['employee', 'board']);
    }

    /**
     * Completar pedido: sólo employee y estado pending.
     */
    public function complete(User $user, Order $order): bool
    {
        return $user->type === 'employee'
            && $order->status === 'pending';
    }

    /**
     * Cancelar pedido: sólo board y estado pending.
     */
    public function cancel(User $user, Order $order): bool
    {
        return $user->type === 'board'
            && $order->status === 'pending';
    }

}