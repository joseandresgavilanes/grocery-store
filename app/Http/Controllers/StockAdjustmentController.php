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

    // public function store(Request $r)
    // {
    //     $this->authorize('create', StockAdjustment::class);

    //     $data = $r->validate([
    //         'product_id'=>'required|exists:products,id',
    //         'new_stock'=>'required|integer|min:0',
    //         'reason'=>'required|string|max:255',
    //     ]);

    //     $product = Product::findOrFail($data['product_id']);
    //     $old     = $product->stock;

    //     $product->update(['stock'=>$data['new_stock']]);

    //     StockAdjustment::create([
    //         'product_id'=>$product->id,
    //         'old_stock'=>$old,
    //         'new_stock'=>$data['new_stock'],
    //         'reason'=>$data['reason'],
    //         'user_id'=>auth()->id(),
    //     ]);

    //     return back()
    //         ->with('alert-type','success')
    //         ->with('alert-msg',"Stock de {$product->name} ajustado de $old a {$data['new_stock']}");
    // }
}