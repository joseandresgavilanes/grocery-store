<x-layouts.main-content title="Editar Tramo de Envío">
  <form
    method="POST"
    action="{{ route('shipping_costs.update', $shippingCost) }}"
    class="max-w-sm space-y-4"
  >
    @csrf
    @method('PUT')

    <div>
      <label class="block mb-1">Valor mínimo (€)</label>
      <input
        type="number"
        name="min_value_threshold"
        step="0.01"
        min="0"
        value="{{ old('min_value_threshold', $shippingCost->min_value_threshold) }}"
        class="form-input w-full"
        required
      >
      @error('min_value_threshold')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="block mb-1">Valor máximo (€)</label>
      <input
        type="number"
        name="max_value_threshold"
        step="0.01"
        min="0"
        value="{{ old('max_value_threshold', $shippingCost->max_value_threshold) }}"
        class="form-input w-full"
        required
      >
      @error('max_value_threshold')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="block mb-1">Coste envío (€)</label>
      <input
        type="number"
        name="shipping_cost"
        step="0.01"
        min="0"
        value="{{ old('shipping_cost', $shippingCost->shipping_cost) }}"
        class="form-input w-full"
        required
      >
      @error('shipping_cost')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <button type="submit" class="btn">Actualizar tramo</button>
  </form>
</x-layouts.main-content>