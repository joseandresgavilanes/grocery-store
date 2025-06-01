<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\OperationFormRequest;

class OperationController extends Controller
{
    public function index(): View
    {
        $txns = Operation::paginate(20);
        return view('transactions.index', compact('txns'));
    }

    public function create(): View
    {
        return view('transactions.create')->with('transaction', new Operation());
    }

    public function store(OperationFormRequest $request): RedirectResponse
    {
        $t = Operation::create($request->validated());
        $url = route('transactions.show', ['transaction' => $t]);
        $msg = "Transacción <a href='$url'><u>#{$t->id}</u></a> creada correctamente.";
        return redirect()->route('transactions.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function show(Operation $transaction): View
    {
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Operation $transaction): View
    {
        return view('transactions.edit', compact('transaction'));
    }

    public function update(OperationFormRequest $request, Operation $transaction): RedirectResponse
    {
        $transaction->update($request->validated());
        $url = route('transactions.show', ['transaction' => $transaction]);
        $msg = "Transacción <a href='$url'><u>#{$transaction->id}</u></a> actualizada correctamente.";
        return redirect()->route('transactions.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function destroy(Operation $transaction): RedirectResponse
    {
        try {
            $transaction->delete();
            $type = 'success';
            $msg  = "Transacción eliminada correctamente.";
        } catch (\Exception $e) {
            $type = 'danger';
            $msg  = "Error al eliminar la transacción.";
        }

        return redirect()->back()
                         ->with('alert-type', $type)
                         ->with('alert-msg', $msg);
    }
}