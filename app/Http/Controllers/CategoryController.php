<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CategoryFormRequest;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::paginate(20);
        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create')->with('category', new Category());
    }

    public function store(CategoryFormRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $filename = \Str::slug($data['name']) . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('categories', $filename, 'public');
            $data['image'] = $filename;
        }

        $cat = Category::create($data);

        $url = route('categories.show', ['category' => $cat]);
        $msg = "Category <a href='$url'><u>{$cat->name}</u></a> created successfully.";

        return redirect()->route('categories.index')
                        ->with('alert-type', 'success')
                        ->with('alert-msg', $msg);
    }



    public function show(Category $category): View
    {
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryFormRequest $request, Category $category): RedirectResponse
{
    $data = $request->validated();

    if ($request->hasFile('image')) {
        $filename = \Str::slug($data['name']) . '.' . $request->file('image')->getClientOriginalExtension();
        $path = $request->file('image')->storeAs('categories', $filename, 'public');

        // Actualiza el nombre de la imagen en $data para que se guarde bien en la DB
        $data['image'] = $filename;
    }

    // Actualizar con $data que contiene el nombre correcto de la imagen
    $category->update($data);

    $url = route('categories.show', ['category' => $category]);
    $msg = "Categoría <a href='$url'><u>{$category->name}</u></a> actualizada correctamente.";

    return redirect()->route('categories.index')
                     ->with('alert-type', 'success')
                     ->with('alert-msg', $msg);
}

    public function destroy(Category $category): RedirectResponse
    {
        try {
            if ($category->products()->count() === 0) {
                $category->delete();
                $type = 'success';
                $msg  = "Categoría {$category->name} eliminada correctamente.";
            } else {
                $type = 'warning';
                $cnt  = $category->products()->count();
                $msg  = "La categoría {$category->name} no puede borrarse porque tiene $cnt productos.";
            }
        } catch (\Exception $e) {
            $type = 'danger';
            $msg  = "Error al eliminar la categoría {$category->name}.";
        }

        return redirect()->back()
                         ->with('alert-type', $type)
                         ->with('alert-msg', $msg);
    }
}
