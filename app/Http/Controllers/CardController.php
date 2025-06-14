<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardFormRequest;
use App\Models\Card;
use App\Models\Operation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\Payment;

class CardController extends Controller
{
    /**
     * Muestra el balance, historial de compras y operaciones.
     */
    public function show(): View
    {
        $user       = Auth::user();
        $card       = $user->card;
        $this->authorize('view', $card);

        // Compras completadas con enlace a su PDF
        $orders     = $user->orders()
                           ->where('status', 'completed')
                           ->orderByDesc('created_at')
                           ->paginate(5);

        // Todas las operaciones de la tarjeta: créditos y débitos
        $operations = $card->operations()
                           ->orderByDesc('created_at')
                           ->paginate(5);

        return view('cards.show', compact('card', 'orders', 'operations'));
    }

    /**
     * Recarga de saldo. Registra una operación de tipo 'credit'.
     */
    public function topup(CardFormRequest $request, Payment $payment): RedirectResponse
    {
        $user   = Auth::user();
        $card   = $user->card;
        $amount = $request->validated()['amount'];

        // Aquí podrías invocar tu servicio de pago real:
        $payment->processTopUp($user, $amount);

        // Actualizamos balance
        $card->increment('balance', $amount);

        // Registramos la operación
        Operation::create([
            'card_id'     => $card->id,
            'type'        => 'credit',
            'amount'      => $amount,
            'description' => 'Recarga de saldo',
        ]);

        return back()->with('success', "Saldo recargado: +€{$amount}");
    }
}