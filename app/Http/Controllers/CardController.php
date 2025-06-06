<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CardFormRequest;

class CardController extends Controller
{
    public function index(): View
    {
        return view('cards.index');
    }

    public function create(): View
    {
        return view('cards.create')->with('card', new Card());
    }

    public function store(CardFormRequest $request): RedirectResponse
    {
        $card = Card::create($request->validated());
        $url  = route('cards.show', ['card' => $card]);
        $msg  = "Tarjeta <a href='$url'><u>{$card->card_number}</u></a> creada correctamente.";
        return redirect()->route('cards.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function show(Card $card): View
    {
        return view('cards.show', compact('card'));
    }

    public function edit(Card $card): View
    {
        return view('cards.edit', compact('card'));
    }

    public function update(CardFormRequest $request, Card $card): RedirectResponse
    {
        $card->update($request->validated());
        $url  = route('cards.show', ['card' => $card]);
        $msg  = "Tarjeta <a href='$url'><u>{$card->card_number}</u></a> actualizada correctamente.";
        return redirect()->route('cards.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function destroy(Card $card): RedirectResponse
    {
        try {
            if ($card->transactions()->count() === 0) {
                $card->delete();
                $type = 'success';
                $msg  = "Tarjeta {$card->card_number} eliminada correctamente.";
            } else {
                $type = 'warning';
                $cnt  = $card->transactions()->count();
                $msg  = "La tarjeta {$card->card_number} no puede borrarse porque tiene $cnt transacciones.";
            }
        } catch (\Exception $e) {
            $type = 'danger';
            $msg  = "Error al eliminar la tarjeta {$card->card_number}.";
        }

        return redirect()->back()
                         ->with('alert-type', $type)
                         ->with('alert-msg', $msg);
    }
}
