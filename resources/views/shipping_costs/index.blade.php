<x-layouts.main-content title="Tramos de Coste de Envío">
  <a href="{{ route('shipping_costs.create') }}" class="btn mb-4">+ Nuevo tramo</a>

  <table class="w-full border-collapse border border-gray-200">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2 border">Desde (€)</th>
        <th class="p-2 border">Hasta (€)</th>
        <th class="p-2 border">Coste (€)</th>
        <th class="p-2 border">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($costs as $c)
      <tr>
        <td class="p-2 border">{{ number_format($c->min_value_threshold,2,',','.') }}</td>
        <td class="p-2 border">
          {{ $c->max_value_threshold !== null 
              ? number_format($c->max_value_threshold,2,',','.') 
              : '∞' 
          }}
        </td>
        <td class="p-2 border">{{ number_format($c->shipping_cost,2,',','.') }}</td>
        <td class="p-2 border space-x-2">
          @can('update',$c)
            <a href="{{ route('shipping_costs.edit',$c) }}" class="btn-xs">Editar</a>
          @endcan
          @can('delete',$c)
            <form action="{{ route('shipping_costs.destroy',$c) }}" method="POST" class="inline">
              @csrf @method('DELETE')
              <button class="btn-xs btn-red">Eliminar</button>
            </form>
          @endcan
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="4" class="p-4 text-center text-gray-500">No hay tramos definidos.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-4">{{ $costs->links() }}</div>
</x-layouts.main-content>