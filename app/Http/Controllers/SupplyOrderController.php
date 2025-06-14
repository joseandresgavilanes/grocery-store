<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SupplyOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SupplyOrderController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', SupplyOrder::class);

        $orders = SupplyOrder::with('product')->latest()->paginate(20);

        return view('supply_orders.index', compact('orders'));
    }

    public function create(): View
    {
        $this->authorize('create', SupplyOrder::class);

        $products = Product::orderBy('name')->paginate(10);

        return view('supply_orders.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', SupplyOrder::class);

        $validated = $request->validate([
            'items' => ['required', 'array'],
        ]);

        $errors = [];

        foreach ($request->input('items', []) as $productId => $qty) {
            if (!$qty || $qty <= 0) continue;

            $product = \App\Models\Product::find($productId);

            if (!$product) {
                $errors["items.$productId"] = "Producto no encontrado.";
                continue;
            }

            if ($product->stock + $qty > $product->stock_upper_limit) {
                $errors["items.$productId"] = "Cantidad excede el límite de stock para {$product->name}. Máximo permitido: " . ($product->stock_upper_limit - $product->stock);
            }
        }

        if (!empty($errors)) {
            return back()
                ->withErrors($errors)
                ->withInput();
        }


        foreach ($request->items as $productId => $qty) {
            if ($qty <= 0) continue;

            SupplyOrder::create([
                'product_id' => $productId,
                'registered_by_user_id' => auth()->id(),
                'quantity' => $qty,
                'status' => 'requested',
            ]);
        }

        return redirect()->route('supply-orders.index')
            ->with('success', 'Órdenes de suministro creadas.');
    }

    public function autoGenerate(): RedirectResponse    
    {
        $this->authorize('create', SupplyOrder::class);

        $userId = auth()->id();

        Product::whereColumn('stock', '<', 'stock_lower_limit')->chunk(50, function ($products) use ($userId) {
            foreach ($products as $product) {
                $alreadyPending = $product->supplyOrders()
                    ->where('status', 'requested')
                    ->exists();

                if ($alreadyPending) {
                    continue;
                }

                $qty = $product->stock_upper_limit - $product->stock;

                if ($qty <= 0) {
                    continue;
                }

                SupplyOrder::create([
                    'product_id' => $product->id,
                    'registered_by_user_id' => $userId,
                    'quantity' => $qty,
                    'status' => 'requested',
                ]);
            }
        });

        return redirect()->route('supply-orders.index')
            ->with('success', 'Órdenes automáticas generadas.');
    }

    public function complete(SupplyOrder $supplyOrder): RedirectResponse
    {
        $this->authorize('complete', $supplyOrder);

        if ($supplyOrder->status === 'completed') {
            return back()->with('warning', "La orden #{$supplyOrder->id} ya estaba completada.");
        }

        $supplyOrder->update(['status' => 'completed']);

        $product = $supplyOrder->product;
        $product->increment('stock', $supplyOrder->quantity);

        return back()->with('success', "Orden #{$supplyOrder->id} completada y stock actualizado.");
    }
    public function destroy(SupplyOrder $supplyOrder): RedirectResponse
    {
        $this->authorize('delete', $supplyOrder);

        if ($supplyOrder->status === 'cancelled') {
            return back()->with('warning', "La orden #{$supplyOrder->id} ya está cancelada.");
        }

        $supplyOrder->update(['status' => 'cancelled']);

        return back()->with('success', "Orden #{$supplyOrder->id} cancelada.");
    }

    //Si no funciona el destroy de arriba, cambiar para que solo borre
    // public function destroy(SupplyOrder $supplyOrder): RedirectResponse
    // {
    //     $this->authorize('delete', $supplyOrder);

    //     $supplyOrder->delete();

    //     return back()->with('success', "Orden #{$supplyOrder->id} eliminada.");
    // }
}
