{{-- resources/views/products/index.blade.php --}}
<x-layouts.main-content title="Catálogo de Productos">
    {{-- 1) Barra de filtros / búsqueda --}}
    <div class="mb-6 flex flex-wrap items-center gap-4">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap items-center gap-2">
            {{-- Filtro: Categoría --}}
            <select name="category_id" class="border-gray-300 rounded px-2 py-1">
                <option value="">Todas las categorías</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            {{-- Búsqueda: Nombre --}}
            <input
                type="text"
                name="q"
                placeholder="Buscar por nombre..."
                value="{{ request('q') }}"
                class="border-gray-300 rounded px-2 py-1"
            >

            {{-- Rango de precio --}}
            <input
                type="number"
                name="price_min"
                placeholder="Precio min"
                value="{{ request('price_min') }}"
                class="w-20 border-gray-300 rounded px-2 py-1"
                step="0.01"
                min="0"
            >
            <span class="mx-1">-</span>
            <input
                type="number"
                name="price_max"
                placeholder="Precio max"
                value="{{ request('price_max') }}"
                class="w-20 border-gray-300 rounded px-2 py-1"
                step="0.01"
                min="0"
            >

            {{-- Ordenamiento --}}
            <select name="sort_by" class="border-gray-300 rounded px-2 py-1">
                <option value="name"  {{ request('sort_by')=='name'  ? 'selected' : '' }}>Nombre</option>
                <option value="price" {{ request('sort_by')=='price' ? 'selected' : '' }}>Precio</option>
                <option value="stock" {{ request('sort_by')=='stock' ? 'selected' : '' }}>Stock</option>
            </select>
            <select name="sort_dir" class="border-gray-300 rounded px-2 py-1">
                <option value="asc"  {{ request('sort_dir')=='asc'  ? 'selected' : '' }}>Ascendente</option>
                <option value="desc" {{ request('sort_dir')=='desc' ? 'selected' : '' }}>Descendente</option>
            </select>

            <button type="submit"
                    class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                Filtrar
            </button>
        </form>
    </div>

    {{-- 2) Grid de productos --}}
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                @include('products.partials.cards', ['product' => $product])
            @endforeach
        </div>
    </div>

    {{-- 3) Paginación --}}
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</x-layouts.main-content>