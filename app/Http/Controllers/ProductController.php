<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProductFormRequest;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        // Construimos query base incluyendo la categoría
        $query = Product::with('category');

        // 1) Aplicar filtro por categoría
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // 2) Búsqueda parcial en nombre (case-insensitive)
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where('name', 'LIKE', "%{$q}%");
        }

        // 3) Rango de precio: price_min | price_max
        if ($request->filled('price_min')) {
            $query->where('price', '>=', floatval($request->input('price_min')));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', floatval($request->input('price_max')));
        }

        // 4) Ordenamiento: sort_by (campo) y sort_dir (asc/desc)
        $allowedSorts = ['name', 'price', 'stock'];
        $sortBy  = in_array($request->input('sort_by'), $allowedSorts) 
                      ? $request->input('sort_by') 
                      : 'name';
        $sortDir = $request->input('sort_dir') === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sortBy, $sortDir);

        // 5) Paginación (20 por página). Conservar query string para links
        $products = $query->paginate(20)
                          ->appends($request->only(['category_id', 'q', 'price_min', 'price_max', 'sort_by', 'sort_dir']));

        // 6) Pasar también la lista de categorías (para dropdown de filtro)
        $categories = Category::orderBy('name')->get(['id','name']);

        return view('products.index', compact('products', 'categories'));
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