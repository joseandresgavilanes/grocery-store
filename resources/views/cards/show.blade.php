<x-layouts.main-content title="Mi Tarjeta Virtual">

  {{-- Saldo actual --}}
  <div class="mb-8 p-6 bg-white rounded-lg shadow-md max-w-md">
    <h2 class="text-2xl font-bold text-gray-800">
      Saldo actual: <span class="text-green-600">{{ number_format($card->balance, 2, ',', '.') }} €</span>
    </h2>
  </div>

  {{-- Formulario de recarga --}}
  <div class="mb-12 max-w-md">
    <h3 class="font-semibold text-lg mb-4 text-gray-700">Recargar saldo</h3>
    <form method="POST" action="{{ route('card.topup') }}" class="flex items-center space-x-3">
      @csrf
      <input
        type="number"
        name="amount"
        step="0.01"
        min="0.01"
        placeholder="Cantidad (€)"
        class="form-input w-36 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
        required
      />
      <button type="submit" class="btn bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md shadow-md transition">
        Recargar
      </button>
    </form>
  </div>

  {{-- Historial de Compras --}}
  <div class="mb-12 max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
    <h3 class="font-semibold text-xl mb-6 text-gray-800">Historial de Compras</h3>
    <table class="w-full border-collapse text-sm">
      <thead class="bg-gray-100 rounded-t-lg">
        <tr>
          <th class="p-3 text-left border-b border-gray-300">#Pedido</th>
          <th class="p-3 text-left border-b border-gray-300">Fecha</th>
          <th class="p-3 text-right border-b border-gray-300">Total</th>
          <th class="p-3 text-center border-b border-gray-300">Recibo</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
          <tr class="hover:bg-green-50 transition cursor-pointer">
            <td class="p-3 border-b border-gray-200">{{ $order->id }}</td>
            <td class="p-3 border-b border-gray-200">{{ $order->created_at->format('Y-m-d') }}</td>
            <td class="p-3 border-b border-gray-200 text-right font-mono text-green-700">
              {{ number_format($order->total, 2, ',', '.') }} €
            </td>
            <td class="p-3 border-b border-gray-200 text-center">
              <a href="{{ route('orders.receipt', $order) }}" class="text-green-600 hover:underline font-semibold">
                Ver PDF
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="p-6 text-center text-gray-400 italic">No hay compras registradas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-4">
      {{ $orders->links() }}
    </div>
  </div>

  {{-- Historial de Operaciones --}}
  <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
    <h3 class="font-semibold text-xl mb-6 text-gray-800">Movimientos de Tarjeta</h3>
    <table class="w-full border-collapse text-sm">
      <thead class="bg-gray-100 rounded-t-lg">
        <tr>
          <th class="p-3 text-left border-b border-gray-300">Fecha</th>
          <th class="p-3 text-left border-b border-gray-300">Tipo</th>
          <th class="p-3 text-right border-b border-gray-300">Cantidad</th>
          <th class="p-3 text-left border-b border-gray-300">Descripción</th>
        </tr>
      </thead>
      <tbody>
        @forelse($operations as $op)
          <tr class="hover:bg-green-50 transition cursor-pointer">
            <td class="p-3 border-b border-gray-200">{{ $op->created_at->format('Y-m-d H:i') }}</td>
            <td class="p-3 border-b border-gray-200 capitalize font-medium">{{ $op->type }}</td>
            <td class="p-3 border-b border-gray-200 text-right font-mono {{ $op->type === 'debit' ? 'text-red-600' : 'text-green-600' }}">
              {{ $op->type === 'debit' ? '-' : '+' }}
              {{ number_format($op->amount, 2, ',', '.') }} €
            </td>
            <td class="p-3 border-b border-gray-200">{{ $op->description }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="p-6 text-center text-gray-400 italic">Sin movimientos en la tarjeta.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-4">
      {{ $operations->links() }}
    </div>
  </div>

</x-layouts.main-content>
