<?php

namespace App\Http\Controllers;

use App\Models\ItemOrder;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ItemOrderFormRequest;

class ItemOrderController extends Controller
{
    public function index(): View
    {
        $items = ItemOrder::paginate(20);
        return view('order_items.index', compact('items'));
    }

    public function create(): View
    {
        return view('order_items.create')->with('orderItem', new ItemOrder());
    }

    public function store(ItemOrderFormRequest $request): RedirectResponse
    {
        $i = ItemOrder::create($request->validated());
        $url = route('order_items.show', ['order_item' => $i]);
        $msg = "Ítem de pedido <a href='$url'><u>#{$i->id}</u></a> creado correctamente.";
        return redirect()->route('order_items.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function show(ItemOrder $orderItem): View
    {
        return view('order_items.show', compact('orderItem'));
    }

    public function edit(ItemOrder $orderItem): View
    {
        return view('order_items.edit', compact('orderItem'));
    }

    public function update(ItemOrderFormRequest $request, ItemOrder $orderItem): RedirectResponse
    {
        $orderItem->update($request->validated());
        $url = route('order_items.show', ['order_item' => $orderItem]);
        $msg = "Ítem de pedido <a href='$url'><u>#{$orderItem->id}</u></a> actualizado correctamente.";
        return redirect()->route('order_items.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function destroy(ItemOrder $orderItem): RedirectResponse
    {
        try {
            $orderItem->delete();
            $type = 'success';
            $msg  = "Ítem de pedido eliminado correctamente.";
        } catch (\Exception $e) {
            $type = 'danger';
            $msg  = "Error al eliminar el ítem de pedido.";
        }

        return redirect()->back()
                         ->with('alert-type', $type)
                         ->with('alert-msg', $msg);
    }
}