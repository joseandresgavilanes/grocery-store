<x-layouts.main-content title="Nueva Orden Manual">
  <form action="{{ route('supply-orders.store') }}" method="POST" class="space-y-6 max-w-2xl mx-auto bg-white p-6 rounded-xl shadow">
    @csrf

    <h2 class="text-xl font-semibold text-gray-800 mb-4">Seleccionar productos</h2>

    @foreach($products as $product)
      <div class="flex flex-col border-b py-2">
        <div class="flex items-center justify-between gap-4">
          <label for="product-{{ $product->id }}" class="text-gray-700 flex-1">
            {{ $product->name }}
          </label>
          <input
            type="number"
            name="items[{{ $product->id }}]"
            id="product-{{ $product->id }}"
            value="{{ old('items.' . $product->id, 0) }}"
            min="0"
            class="w-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500
              @error('items.' . $product->id) border-red-500 @enderror"
          >
        </div>
        {{-- Mostrar error específico por producto --}}
        @error('items.' . $product->id)
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>
    @endforeach

    {{-- Paginación --}}
    <div class="pt-2">
      {{ $products->links('vendor.pagination.tailwind') }}
    </div>

    <div class="pt-4 flex justify-end">
      <a href="{{ route('supply-orders.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition">
        Cancelar
      </a>
      <button type="submit" class="ml-3 px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium">
        Guardar órdenes
      </button>
    </div>
  </form>
</x-layouts.main-content>
