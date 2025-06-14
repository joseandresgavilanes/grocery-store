<?php
namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use App\Models\Product;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{

    public function store(Request $request, Product $product)
{
    $this->authorize('create', StockAdjustment::class);

    $newQty = (int)$request->input('new_stock');
    $delta = $newQty - $product->stock;

    $product->update(['stock' => $newQty]);
    StockAdjustment::create([
        'product_id'          => $product->id,
        'registered_by_user_id'=> auth()->id(),
        'quantity_changed'    => $delta,
    ]);

    return back()->with('success', "Stock de {$product->name} ajustado en {$delta} unidades.");
}


}
