<x-layouts.main-content title="Mi Tarjeta Virtual">

  {{-- Saldo actual --}}
  <div class="mb-6">
    <h2 class="text-xl font-semibold">
      Saldo actual: {{ number_format($card->balance,2,',','.') }} €
    </h2>
  </div>

  {{-- Formulario de recarga --}}
  <div class="mb-8">
    <h3 class="font-semibold mb-2">Recargar saldo</h3>
    <form method="POST" action="{{ route('card.topup') }}" class="flex items-center space-x-2">
      @csrf
      <input
        type="number"
        name="amount"
        step="0.01"
        min="0.01"
        placeholder="Cantidad (€)"
        class="form-input w-32"
        required
      />
      <button type="submit" class="btn bg-green-600 text-white">Recargar</button>
    </form>
  </div>

  {{-- Historial de Compras --}}
  <div class="mb-8">
    <h3 class="font-semibold mb-2">Historial de Compras</h3>
    <table class="w-full border-collapse border border-gray-200 text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 border">#Pedido</th>
          <th class="p-2 border">Fecha</th>
          <th class="p-2 border">Total</th>
          <th class="p-2 border">Recibo</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
          <tr>
            <td class="p-2 border">{{ $order->id }}</td>
            <td class="p-2 border">{{ $order->created_at->format('Y-m-d') }}</td>
            <td class="p-2 border text-right">{{ number_format($order->total,2,',','.') }} €</td>
            <td class="p-2 border text-center">
             <a href="{{ route('orders.receipt', $order) }}"
   class="text-blue-600 underline"
>Ver PDF</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="p-4 text-center text-gray-500">No hay compras registradas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Historial de Operaciones --}}
  <div>
    <h3 class="font-semibold mb-2">Movimientos de Tarjeta</h3>
    <table class="w-full border-collapse border border-gray-200 text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 border">Fecha</th>
          <th class="p-2 border">Tipo</th>
          <th class="p-2 border">Cantidad</th>
          <th class="p-2 border">Descripción</th>
        </tr>
      </thead>
      <tbody>
        @forelse($operations as $op)
          <tr>
            <td class="p-2 border">{{ $op->created_at->format('Y-m-d H:i') }}</td>
            <td class="p-2 border">{{ ucfirst($op->type) }}</td>
            <td class="p-2 border text-right">
              {{ $op->type === 'debit' ? '-' : '+' }}
              {{ number_format($op->value,2,',','.') }} €
            </td>
            <td class="p-2 border">{{ $op->description }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="p-4 text-center text-gray-500">Sin movimientos en la tarjeta.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</x-layouts.main-content>
