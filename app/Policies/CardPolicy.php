<?php

// app/Policies/CardPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\Card;

class CardPolicy
{
    /**
     * Solo el dueño de la tarjeta (user.id === card.id) puede verla.
     */
    public function view(User $user, Card $card): bool
    {
        return $user->id === $card->id;
    }
}