<?php
namespace App\Http\Controllers;

use App\Models\SupplyOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplyOrderController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', SupplyOrder::class);
        $orders = SupplyOrder::with('product')->latest()->get();
        return view('supply_orders.index', compact('orders'));
    }

    public function create()
    {
        $this->authorize('create', SupplyOrder::class);
        $products = Product::orderBy('name')->get();
        return view('supply_orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', SupplyOrder::class);

        foreach ($request->input('items') as $prodId => $qty) {
            SupplyOrder::create([
                'product_id'            => $prodId,
                'registered_by_user_id' => auth()->id(),
                'quantity'              => $qty,
                'status'                => 'requested',
            ]);
        }

        return redirect()->route('supply-orders.index')
            ->with('success', 'Órdenes de suministro creadas.');
    }

/** Automático: repone hasta stock_upper_limit */
    public function autoGenerate()
    {
        $this->authorize('create', SupplyOrder::class);

        $toRestock = Product::whereColumn('stock', '<', 'stock_lower_limit')->get();
        foreach ($toRestock as $product) {
            $qty = $product->stock_upper_limit - $product->stock;
            SupplyOrder::create([
                'product_id'            => $product->id,
                'registered_by_user_id' => auth()->id(),
                'quantity'              => $qty,
                'status'                => 'requested',
            ]);
        }

        return redirect()->route('supply-orders.index')
            ->with('success', 'Órdenes automáticas generadas.');
    }

    public function complete(SupplyOrder $supplyOrder)
    {
        $this->authorize('complete', $supplyOrder);

        $supplyOrder->update(['status' => 'completed']);
        $product = $supplyOrder->product;
        $product->increment('stock', $supplyOrder->quantity);

        return back()->with('success', "Orden #{$supplyOrder->id} completada y stock actualizado.");
    }

    public function destroy(SupplyOrder $supplyOrder)
    {
        $this->authorize('delete', $supplyOrder);
        $supplyOrder->delete();
        return back()->with('success', "Orden #{$supplyOrder->id} eliminada.");
    }
}
