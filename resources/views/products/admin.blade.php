<x-layouts.main-content title="Productos (Admin)">
  <div class="mb-4 flex justify-between">
    <h2 class="text-xl">Productos</h2>
    @can('create', App\Models\Product::class)
      <a href="{{ route('products.create') }}" class="btn">+ Nuevo</a>
    @endcan
  </div>

  <form method="GET" action="{{ route('products.admin') }}" class="mb-6 flex gap-2">
    <select name="category_id" class="form-input">
      <option value="">Todas categorías</option>
      @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id?'selected':'' }}>
          {{ $cat->name }}
        </option>
      @endforeach
    </select>
    <input type="text" name="q" placeholder="Buscar..." class="form-input" value="{{ request('q') }}"/>
    <button class="btn">Filtrar</button>
  </form>

  <table class="w-full border-collapse border border-gray-200">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2 border">Nombre</th>
        <th class="p-2 border">Categoría</th>
        <th class="p-2 border">Precio</th>
        <th class="p-2 border">Stock</th>
        <th class="p-2 border">Límites</th>
        <th class="p-2 border">Descuento</th>
        <th class="p-2 border">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $p)
      <tr>
        <td class="p-2 border">{{ $p->name }}</td>
        <td class="p-2 border">{{ $p->category->name }}</td>
        <td class="p-2 border">{{ number_format($p->price,2,',','.') }} €</td>
        <td class="p-2 border">{{ $p->stock }}</td>
        <td class="p-2 border">{{ $p->stock_lower_limit }}–{{ $p->stock_upper_limit }}</td>
        <td class="p-2 border">
          @if($p->discount_min_qty && $p->discount)
            {{ ($p->discount*100).'%' }} desde {{ $p->discount_min_qty }}
          @else
            —
          @endif
        </td>
        <td class="p-2 border space-x-1">
          @can('update',$p)
            <a href="{{ route('products.edit',$p) }}" class="btn-xs">Editar</a>
          @endcan
          @can('delete',$p)
            <form action="{{ route('products.destroy',$p) }}" method="POST" class="inline">
              @csrf @method('DELETE')
              <button class="btn-xs btn-red">Eliminar</button>
            </form>
          @endcan
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mt-4">
    {{ $products->links() }}
  </div>
</x-layouts.main-content>