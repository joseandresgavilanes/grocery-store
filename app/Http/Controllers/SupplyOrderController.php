<?php

namespace App\Http\Controllers;

use App\Models\SupplyOrder;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\SupplyOrderFormRequest;

class SupplyOrderController extends Controller
{
    public function index(): View
    {
        $orders = SupplyOrder::paginate(20);
        return view('supply_orders.index', compact('orders'));
    }

    public function create(): View
    {
        return view('supply_orders.create')->with('supplyOrder', new SupplyOrder());
    }

    public function store(SupplyOrderFormRequest $request): RedirectResponse
    {
        $o = SupplyOrder::create($request->validated());
        $url = route('supply_orders.show', ['supply_order' => $o]);
        $msg = "Pedido de reposición <a href='$url'><u>#{$o->id}</u></a> creado correctamente.";
        return redirect()->route('supply_orders.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function show(SupplyOrder $supplyOrder): View
    {
        return view('supply_orders.show', compact('supplyOrder'));
    }

    public function edit(SupplyOrder $supplyOrder): View
    {
        return view('supply_orders.edit', compact('supplyOrder'));
    }

    public function update(SupplyOrderFormRequest $request, SupplyOrder $supplyOrder): RedirectResponse
    {
        $supplyOrder->update($request->validated());
        $url = route('supply_orders.show', ['supply_order' => $supplyOrder]);
        $msg = "Pedido de reposición <a href='$url'><u>#{$supplyOrder->id}</u></a> actualizado correctamente.";
        return redirect()->route('supply_orders.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function destroy(SupplyOrder $supplyOrder): RedirectResponse
    {
        try {
            $supplyOrder->delete();
            $type = 'success';
            $msg  = "Pedido de reposición eliminado correctamente.";
        } catch (\Exception $e) {
            $type = 'danger';
            $msg  = "Error al eliminar el pedido de reposición.";
        }

        return redirect()->back()
                         ->with('alert-type', $type)
                         ->with('alert-msg', $msg);
    }
}