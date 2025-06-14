<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProductFormRequest;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where('name', 'LIKE', "%{$q}%");
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', floatval($request->input('price_min')));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', floatval($request->input('price_max')));
        }

        $allowedSorts = ['name', 'price', 'stock'];
        $sortBy  = in_array($request->input('sort_by'), $allowedSorts)
                      ? $request->input('sort_by')
                      : 'name';
        $sortDir = $request->input('sort_dir') === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sortBy, $sortDir);

        $products = $query->paginate(20)
                          ->appends($request->only(['category_id', 'q', 'price_min', 'price_max', 'sort_by', 'sort_dir']));

        $categories = Category::orderBy('name')->get(['id','name']);

        return view('products.index', compact('products', 'categories'));
    }

public function show(Product $product)
{
    $product->load('category');
    return view('products.show', compact('product'));
}

    public function create(): View
    {
        $this->authorize('create', Product::class);
        $categories = Category::orderBy('name')->get(['id','name']);
        return view('products.create', compact('categories'));
    }

    public function store(ProductFormRequest $request): RedirectResponse
    {
        $this->authorize('create', Product::class);

        $data = $request->validated();
        if ($file = $request->file('photo')) {
            $data['photo'] = $file->storeAs('products', $file->hashName(), 'public');
            $data['photo'] = $file->hashName();
        }

        $p = Product::create($data);

        return redirect()->route('products.index')
                         ->with('success',"Producto '{$p->name}' creado.");
    }

    public function edit(Product $product): View
    {
        $this->authorize('update', $product);
        $categories = Category::orderBy('name')->get(['id','name']);
        return view('products.edit', compact('product','categories'));
    }

    public function update(ProductFormRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $filename = Str::slug($data['name']) . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('products', $filename, 'public');
            $data['photo'] = $filename;
        }

        $product->update($data);

        return redirect()->route('products.admin')
                        ->with('success', "Product '{$product->name}' updated.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        if ($product->orderItems()->count() === 0
         && $product->supplyOrderItems()->count() === 0) {
            $product->delete();
            $msg = "Producto '{$product->name}' eliminado.";
            $type = 'success';
        } else {
            $msg = "No se puede eliminar '{$product->name}' por registros asociados.";
            $type = 'warning';
        }

        return back()->with('alert-type',$type)
                     ->with('alert-msg',$msg);
    }

    public function adminIndex(Request $request): View
    {
        $this->authorize('viewAny', Product::class);

        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('q')) {
            $query->where('name','LIKE',"%{$request->q}%");
        }

        $products = $query->orderBy('name')
                          ->paginate(20)
                          ->appends($request->only(['category_id','q']));

        $categories = Category::orderBy('name')->get(['id','name']);

        return view('products.admin', compact('products','categories'));
    }
}
