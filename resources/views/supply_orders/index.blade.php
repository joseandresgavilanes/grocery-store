<x-layouts.main-content title="Órdenes de suministro">
  <div class="mb-6 flex items-center gap-3">
    @can('create', App\Models\SupplyOrder::class)
      <a href="{{ route('supply-orders.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
        Nuevo pedido
      </a>
      <form action="{{ route('supply-orders.autoGenerate') }}" method="POST" class="inline">
        @csrf
        <button class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition">
          Generar automáticas
        </button>
      </form>
    @endcan
  </div>

  <div class="overflow-x-auto rounded-lg shadow">
    <table class="w-full text-left text-sm text-gray-700 bg-white">
      <thead class="bg-gray-100 text-xs uppercase text-gray-500 border-b">
        <tr>
          <th class="px-4 py-3">#</th>
          <th class="px-4 py-3">Producto</th>
          <th class="px-4 py-3">Cantidad</th>
          <th class="px-4 py-3">Estado</th>
          <th class="px-4 py-3">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $o)
        <tr class="border-b hover:bg-gray-50">
          <td class="px-4 py-2">{{ $o->id }}</td>
          <td class="px-4 py-2">{{ $o->product->name }}</td>
          <td class="px-4 py-2">{{ $o->quantity }}</td>
          <td class="px-4 py-2">
            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold 
              {{ $o->status === 'completo' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
              {{ ucfirst($o->status) }}
            </span>
          </td>
          <td class="px-4 py-2 flex gap-2">
            @can('complete', $o)
              <form action="{{ route('supply-orders.complete', $o) }}" method="POST">
                @csrf
                <button class="text-xs px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                  Completar
                </button>
              </form>
            @endcan
            @can('delete', $o)
              <form action="{{ route('supply-orders.destroy', $o) }}" method="POST">
                @csrf @method('DELETE')
                <button class="text-xs px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                  Borrar
                </button>
              </form>
            @endcan
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="mt-4">
      {{ $orders->links() }}
    </div>
    
  </div>
</x-layouts.main-content>
