<x-layouts.main-content title="Pedidos pendientes">

  <div class="overflow-x-auto">
    <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-2 border-b border-gray-300 text-left text-sm font-semibold text-gray-700">#Pedido</th>
          <th class="px-4 py-2 border-b border-gray-300 text-left text-sm font-semibold text-gray-700">Socio</th>
          <th class="px-4 py-2 border-b border-gray-300 text-left text-sm font-semibold text-gray-700">Fecha</th>
          <th class="px-4 py-2 border-b border-gray-300 text-right text-sm font-semibold text-gray-700">Total</th>
          <th class="px-4 py-2 border-b border-gray-300 text-center text-sm font-semibold text-gray-700">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 border-b border-gray-200 text-gray-800 text-sm text-center">{{ $order->id }}</td>
            <td class="px-4 py-3 border-b border-gray-200 text-gray-800 text-sm">{{ $order->member->name }}</td>
            <td class="px-4 py-3 border-b border-gray-200 text-gray-800 text-sm">{{ $order->created_at->format('Y-m-d') }}</td>
            <td class="px-4 py-3 border-b border-gray-200 text-gray-800 text-sm text-right">{{ number_format($order->total, 2, ',', '.') }} €</td>
            <td class="px-4 py-3 border-b border-gray-200 text-center space-x-2 flex justify-center">

              <form action="{{ route('orders.complete', $order) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="px-3 py-1 rounded-md bg-green-600 text-white text-sm font-medium hover:bg-green-700 disabled:bg-green-300 disabled:cursor-not-allowed transition"
                        {{ $order->items->contains(fn($i)=> $i->quantity > $i->product->stock) ? 'disabled' : '' }}>
                  Completar
                </button>
              </form>


              @can('cancel', $order)
                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                  @csrf
                  <button type="submit"
                          class="px-3 py-1 rounded-md bg-yellow-500 text-white text-sm font-medium hover:bg-yellow-600 transition">
                    Cancelar
                  </button>
                </form>
              @endcan
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="p-6 text-center text-gray-500 italic">No hay pedidos pendientes.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</x-layouts.main-content>
