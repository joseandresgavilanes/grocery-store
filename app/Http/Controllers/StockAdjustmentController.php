<?php
namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use App\Models\Product;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    public function store(Request $r)
    {
        $this->authorize('create', StockAdjustment::class);

        $data = $r->validate([
            'product_id'=>'required|exists:products,id',
            'new_stock'=>'required|integer|min:0',
            'reason'=>'required|string|max:255',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $old     = $product->stock;

        $product->update(['stock'=>$data['new_stock']]);

        StockAdjustment::create([
            'product_id'=>$product->id,
            'old_stock'=>$old,
            'new_stock'=>$data['new_stock'],
            'reason'=>$data['reason'],
            'user_id'=>auth()->id(),
        ]);

        return back()
            ->with('alert-type','success')
            ->with('alert-msg',"Stock de {$product->name} ajustado de $old a {$data['new_stock']}");
    }
}