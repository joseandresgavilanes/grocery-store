<x-layouts.main-content title="Órdenes de suministro">
  <div class="mb-4 space-x-2">
    @can('create', App\Models\SupplyOrder::class)
      <a href="{{ route('supply-orders.create') }}" class="btn">Nueva manual</a>
      <form action="{{ route('supply-orders.auto') }}" method="POST" class="inline">
        @csrf
        <button class="btn">Generar automáticas</button>
      </form>
    @endcan
  </div>
  <table class="w-full">
    <thead><tr>
      <th>#</th><th>Producto</th><th>Cantidad</th><th>Estado</th><th>Acciones</th>
    </tr></thead>
    <tbody>
    @foreach($orders as $o)
      <tr>
        <td>{{ $o->id }}</td>
        <td>{{ $o->product->name }}</td>
        <td>{{ $o->quantity }}</td>
        <td>{{ $o->status }}</td>
        <td class="space-x-2">
          @can('complete', $o)
            <form action="{{ route('supply-orders.complete', $o) }}" method="POST">@csrf
              <button class="btn-xs">Completar</button>
            </form>
          @endcan
          @can('delete', $o)
            <form action="{{ route('supply-orders.destroy', $o) }}" method="POST">@csrf @method('DELETE')
              <button class="btn-xs btn-red">Borrar</button>
            </form>
          @endcan
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</x-layouts.main-content>