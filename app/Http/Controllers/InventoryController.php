<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    public function index(Request $request)
    {
        // Todos los productos, con opción de filtrar
        $query = Product::query();
        if ($request->filled('filter') && $request->filter === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        } elseif ($request->filled('filter') && $request->filter === 'low_stock') {
            $query->whereColumn('stock', '<', 'stock_lower_limit');
        }
        $products = $query->orderBy('name')->get();
        return view('inventory.index', compact('products'));
    }


    // public function index(Request $request)
    // {
    //     $query = Product::query();

    //     // filtro “sin stock”
    //     if ($request->filled('filter') && $request->filter==='out_of_stock') {
    //         $query->where('stock', 0);
    //     }

    //     // filtro “debajo mínimo”
    //     if ($request->filled('filter') && $request->filter==='below_min') {
    //         $query->whereColumn('stock','<','stock_min_qty');
    //     }

    //     $products = $query->paginate(20)
    //         ->withQueryString();

    //     return view('inventory.index', compact('products'));
    // }

    /** genera supply-orders automáticos hasta stock_max */
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
}