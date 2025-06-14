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

public function show(Product $product)
{
    $product->load('category');
    return view('products.show', compact('product'));
}

/**
     * Formulario de creación.
     */
    public function create(): View
    {
        $this->authorize('create', Product::class);
        $categories = Category::orderBy('name')->get(['id','name']);
        return view('products.create', compact('categories'));
    }

    /**
     * Almacenar nuevo producto.
     */
    public function store(ProductFormRequest $request): RedirectResponse
    {
        $this->authorize('create', Product::class);

        $data = $request->validated();
        if ($file = $request->file('photo')) {
            $data['photo'] = $file->storeAs('products', $file->hashName(), 'public');
            $data['photo'] = $file->hashName(); // Guardas solo el nombre

        }

        $p = Product::create($data);

        return redirect()->route('products.index')
                         ->with('success',"Producto '{$p->name}' creado.");
    }

    /**
     * Formulario de edición.
     */
    public function edit(Product $product): View
    {
        $this->authorize('update', $product);
        $categories = Category::orderBy('name')->get(['id','name']);
        return view('products.edit', compact('product','categories'));
    }

    /**
     * Actualizar producto.
     */
    public function update(ProductFormRequest $request, Product $product): RedirectResponse
{
    $this->authorize('update', $product);

    $data = $request->validated();

    if ($request->hasFile('photo')) {
        // Generar nombre amigable para la imagen basado en el nombre del producto
        $filename = Str::slug($data['name']) . '.' . $request->file('photo')->getClientOriginalExtension();

        // Guardar la imagen con ese nombre en 'products' dentro del disco 'public'
        $path = $request->file('photo')->storeAs('products', $filename, 'public');

        // Actualizar el nombre de la foto en los datos para guardar en BD
        $data['photo'] = $filename;

        // Opcional: eliminar la imagen anterior si quieres evitar acumulación
        // Storage::disk('public')->delete("products/{$product->photo}");
    }

    $product->update($data);

    return redirect()->route('products.admin')
                     ->with('success', "Product '{$product->name}' updated.");
}

    /**
     * Eliminar producto (soft o hard según tu implementación).
     */
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

    // Para el filtro de categorías en el dropdown:
    $categories = Category::orderBy('name')->get(['id','name']);

    return view('products.admin', compact('products','categories'));
}
}
