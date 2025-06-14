<x-layouts.main-content title="Productos (Admin)">
  <div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-semibold text-gray-800">Productos</h2>
    @can('create', App\Models\Product::class)
      <a href="{{ route('products.create') }}"
         class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
         + Nuevo
      </a>
    @endcan
  </div>

  <form method="GET" action="{{ route('products.admin') }}" class="mb-6 flex flex-wrap gap-3 items-center">
    <select name="category_id"
            class="border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option value="">Todas categorías</option>
      @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
          {{ $cat->name }}
        </option>
      @endforeach
    </select>

    <input type="text" name="q" placeholder="Buscar..."
           value="{{ request('q') }}"
           class="border border-gray-300 rounded-md px-3 py-2 w-48 focus:outline-none focus:ring-2 focus:ring-blue-500"/>

    <button type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
      Filtrar
    </button>
  </form>

  <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
    <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-700">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-3 font-medium border-b border-gray-200">Nombre</th>
          <th class="px-4 py-3 font-medium border-b border-gray-200">Categoría</th>
          <th class="px-4 py-3 font-medium border-b border-gray-200">Precio</th>
          <th class="px-4 py-3 font-medium border-b border-gray-200">Stock</th>
          <th class="px-4 py-3 font-medium border-b border-gray-200">Límites</th>
          <th class="px-4 py-3 font-medium border-b border-gray-200">Descuento</th>
          <th class="px-4 py-3 font-medium border-b border-gray-200">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @foreach($products as $p)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-2 border-b border-gray-200">{{ $p->name }}</td>
            <td class="px-4 py-2 border-b border-gray-200">{{ $p->category->name }}</td>
            <td class="px-4 py-2 border-b border-gray-200">{{ number_format($p->price, 2, ',', '.') }} €</td>
            <td class="px-4 py-2 border-b border-gray-200">{{ $p->stock }}</td>
            <td class="px-4 py-2 border-b border-gray-200">{{ $p->stock_lower_limit }}–{{ $p->stock_upper_limit }}</td>
            <td class="px-4 py-2 border-b border-gray-200">
              @if($p->discount_min_qty && $p->discount)
                {{ ($p->discount * 100) . '%' }} desde {{ $p->discount_min_qty }}
              @else
                —
              @endif
            </td>
            <td class="px-4 py-2 border-b border-gray-200 space-x-2 whitespace-nowrap">
              @can('update', $p)
                <a href="{{ route('products.edit', $p) }}"
                   class="inline-block px-3 py-1 text-xs font-semibold text-white bg-indigo-600 rounded hover:bg-indigo-700 transition">
                   Editar
                </a>
              @endcan
              @can('delete', $p)
                <form action="{{ route('products.destroy', $p) }}" method="POST" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="inline-block px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 transition">
                    Eliminar
                  </button>
                </form>
              @endcan
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $products->links() }}
  </div>
</x-layouts.main-content>
