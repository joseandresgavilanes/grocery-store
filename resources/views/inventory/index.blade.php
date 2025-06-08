<x-layouts.main-content title="Inventario">
  <div class="flex gap-4 mb-4">
    <a href="{{ route('inventory.index') }}" class="btn">Todos</a>
    <a href="{{ route('inventory.index', ['filter'=>'out']) }}" class="btn">Sin stock</a>
    <a href="{{ route('inventory.index', ['filter'=>'low']) }}" class="btn">Bajo umbral</a>
    <form method="POST" action="{{ route('inventory.autoReorder') }}">
      @csrf
      <button class="btn-primary">Auto-reorder</button>
    </form>
  </div>

  <table class="w-full border">
    <thead>
      <tr>
        <th>Producto</th><th>Categoría</th><th>Stock</th><th>Límite mínimo</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $p)
      <tr>
        <td>{{ $p->name }}</td>
        <td>{{ $p->category->name }}</td>
        <td>{{ $p->stock }}</td>
        <td>{{ $p->stock_lower_limit }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mt-4">{{ $products->links() }}</div>
</x-layouts.main-content>