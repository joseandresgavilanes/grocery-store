<x-layouts.main-content title="Inventario">
  <div class="mb-4">
    <a href="{{ route('inventory.index', ['filter'=>'out_of_stock']) }}" class="btn">Sin stock</a>
    <a href="{{ route('inventory.index', ['filter'=>'low_stock']) }}" class="btn">Bajo límite</a>
    <a href="{{ route('inventory.index') }}" class="btn">Todos</a>
  </div>
  <table class="w-full">
    <thead><tr>
      <th>Producto</th><th>Stock</th><th>Límite</th><th>Ajustar</th>
    </tr></thead>
    <tbody>
    @foreach($products as $p)
      <tr>
        <td>{{ $p->name }}</td>
        <td>{{ $p->stock }}</td>
        <td>{{ $p->stock_lower_limit }}–{{ $p->stock_upper_limit }}</td>
        <td>
          <form action="{{ route('inventory.adjust', $p) }}" method="POST" class="flex">
            @csrf
            <input type="number" name="new_stock" value="{{ $p->stock }}" class="w-16">
            <button class="btn-xs">OK</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</x-layouts.main-content>