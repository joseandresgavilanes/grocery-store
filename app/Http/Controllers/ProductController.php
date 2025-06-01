<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProductFormRequest;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::paginate(20);
        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        return view('products.create')->with('product', new Product());
    }

    public function store(ProductFormRequest $request): RedirectResponse
    {
        $p = Product::create($request->validated());
        $url = route('products.show', ['product' => $p]);
        $msg = "Producto <a href='$url'><u>{$p->name}</u></a> creado correctamente.";
        return redirect()->route('products.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(ProductFormRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());
        $url = route('products.show', ['product' => $product]);
        $msg = "Producto <a href='$url'><u>{$product->name}</u></a> actualizado correctamente.";
        return redirect()->route('products.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function destroy(Product $product): RedirectResponse
    {
        try {
            if ($product->orderItems()->count() === 0
             && $product->supplyOrderItems()->count() === 0) {
                $product->delete();
                $type = 'success';
                $msg  = "Producto {$product->name} eliminado correctamente.";
            } else {
                $type = 'warning';
                $msg  = "El producto {$product->name} no puede borrarse por registros asociados.";
            }
        } catch (\Exception $e) {
            $type = 'danger';
            $msg  = "Error al eliminar el producto {$product->name}.";
        }

        return redirect()->back()
                         ->with('alert-type', $type)
                         ->with('alert-msg', $msg);
    }
}