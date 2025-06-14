<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('filter') && $request->filter === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        }

        if ($request->filled('filter') && $request->filter === 'low_stock') {
            $query->whereColumn('stock', '<', 'stock_lower_limit');
        }

        $products = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('inventory.index', compact('products'));
    }

    public function autoReorder()
    {
        $toReorder = Product::whereColumn('stock','<','stock_min_qty')->get();

        foreach ($toReorder as $product) {
            $qty = $product->stock_max_qty - $product->stock;
            \App\Models\SupplyOrder::create([
                'product_id'=>$product->id,
                'quantity'=>$qty,
                'status'=>'requested',
            ]);
        }

        return back()
            ->with('alert-type','success')
            ->with('alert-msg','Supply orders auto generados');
    }

    public function adjust(Request $request, Product $product)
    {
    $max = $product->stock_upper_limit;

    $validated = $request->validate([
        'new_stock' => ['required', 'integer', 'min:0', "max:$max"],
    ], [
        'new_stock.max' => "El stock no puede superar el límite máximo de $max unidades.",
    ]);

    $product->stock = $validated['new_stock'];
    $product->save();

    return redirect()->back()->with('alert-msg', 'Stock actualizado correctamente.');
    }
}
