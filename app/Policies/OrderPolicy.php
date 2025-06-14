<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    /**
     * ¿Puede ver la lista de pedidos pendientes?
     */
    public function viewAny(User $user): bool
    {
        return $user->isEmployee() || $user->isBoard();
    }

    /**
     * ¿Puede completar este pedido?
     */
    public function complete(User $user, Order $order): bool
    {
        return ($user->isEmployee() || $user->isBoard())
            && $order->status === 'pending'
            && $order->items->every(fn($item) => $item->product->stock >= $item->quantity);
    }

    /**
     * ¿Puede cancelar este pedido?
     */
    public function cancel(User $user, Order $order): bool
    {
        return $user->isBoard()
            && $order->status === 'pending';
    }

    public function view(User $user, Order $order): bool
    {
        if ($user->id === $order->member_id) {
            return true;
        }

        return $user->isEmployee() || $user->isBoard();
    }
}