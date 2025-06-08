<x-layouts.main-content title="Pedidos pendientes">
  <div class="mb-4">
    <a href="{{ route('dashboard') }}" class="btn-secondary">Volver al Dashboard</a>
  </div>

  <table class="w-full border-collapse border border-gray-200">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2 border">#Pedido</th>
        <th class="p-2 border">Socio</th>
        <th class="p-2 border">Fecha</th>
        <th class="p-2 border">Total</th>
        <th class="p-2 border">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $order)
        <tr>
          <td class="p-2 border text-center">{{ $order->id }}</td>
          <td class="p-2 border">{{ $order->member->name }}</td>
          <td class="p-2 border">{{ $order->created_at->format('Y-m-d') }}</td>
          <td class="p-2 border text-right">{{ number_format($order->total, 2, ',', '.') }} €</td>
          <td class="p-2 border flex space-x-2 justify-center">
            {{-- Botón Completar --}}
            <form action="{{ route('orders.complete', $order) }}" method="POST">
              @csrf
              <button type="submit"
                      class="btn-success"
                      {{ $order->items->contains(fn($i)=> $i->quantity > $i->product->stock) ? 'disabled' : '' }}>
                Completar
              </button>
            </form>

            {{-- Botón Cancelar (solo board) --}}
            @can('cancel-orders')
              <form action="{{ route('orders.cancel', $order) }}" method="POST">
                @csrf
                <button type="submit" class="btn-warning">
                  Cancelar
                </button>
              </form>
            @endcan
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="p-4 text-center text-gray-500">No hay pedidos pendientes.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-4">
    {{ $orders->links() }}
  </div>
</x-layouts.main-content>