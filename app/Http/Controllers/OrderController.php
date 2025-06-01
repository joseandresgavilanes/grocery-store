<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\OrderFormRequest;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::paginate(20);
        return view('orders.index', compact('orders'));
    }

    public function create(): View
    {
        return view('orders.create')->with('order', new Order());
    }

    public function store(OrderFormRequest $request): RedirectResponse
    {
        $o = Order::create($request->validated());
        $url = route('orders.show', ['order' => $o]);
        $msg = "Pedido <a href='$url'><u>#{$o->id}</u></a> creado correctamente.";
        return redirect()->route('orders.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function show(Order $order): View
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order): View
    {
        return view('orders.edit', compact('order'));
    }

    public function update(OrderFormRequest $request, Order $order): RedirectResponse
    {
        $order->update($request->validated());
        $url = route('orders.show', ['order' => $order]);
        $msg = "Pedido <a href='$url'><u>#{$order->id}</u></a> actualizado correctamente.";
        return redirect()->route('orders.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function destroy(Order $order): RedirectResponse
    {
        try {
            if ($order->items()->count() === 0) {
                $order->delete();
                $type = 'success';
                $msg  = "Pedido #{$order->id} eliminado correctamente.";
            } else {
                $type = 'warning';
                $cnt  = $order->items()->count();
                $msg  = "El pedido #{$order->id} no puede borrarse porque tiene $cnt ítems.";
            }
        } catch (\Exception $e) {
            $type = 'danger';
            $msg  = "Error al eliminar el pedido #{$order->id}.";
        }

        return redirect()->back()
                         ->with('alert-type', $type)
                         ->with('alert-msg', $msg);
    }
}