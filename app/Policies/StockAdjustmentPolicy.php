<?php
// app/Policies/StockAdjustmentPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\StockAdjustment;

class StockAdjustmentPolicy
{
    /**
     * Registrar un ajuste de stock: employee o board.
     */
    public function create(User $user): bool
    {
        return $user->isEmployee() || $user->isBoard();
    }

    // Si necesitas ver histórico:
    public function viewAny(User $user): bool
    {
        return $user->isEmployee() || $user->isBoard();
    }
}