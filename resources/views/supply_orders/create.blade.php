<x-layouts.main-content title="Crear órdenes de suministro">
  <form action="{{ route('supply-orders.store') }}" method="POST">
    @csrf
    <table class="w-full">
      <thead><tr>
        <th>Producto</th><th>Stock actual</th><th>Pedido (qty)</th>
      </tr></thead>
      <tbody>
      @foreach($products as $p)
        <tr>
          <td>{{ $p->name }}</td>
          <td>{{ $p->stock }}</td>
          <td>
            <input type="number" name="items[{{ $p->id }}]" value="0" min="0" class="w-16">
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
    <button class="btn mt-4">Crear órdenes</button>
  </form>
</x-layouts.main-content>