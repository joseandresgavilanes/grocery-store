<x-layouts.main-content title="Nuevo Producto">
  <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="max-w-lg space-y-4">
    @csrf

    {{-- Categoría --}}
    <div>
      <label class="block mb-1">Categoría</label>
      <select name="category_id" class="form-input w-full" required>
        <option value="">-- Selecciona categoría --</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id ? 'selected' : '' }}>
            {{ $cat->name }}
          </option>
        @endforeach
      </select>
    </div>

    {{-- Nombre --}}
    <div>
      <label class="block mb-1">Nombre</label>
      <input
        type="text"
        name="name"
        value="{{ old('name') }}"
        class="form-input w-full"
        required
      />
    </div>

    {{-- Precio --}}
    <div>
      <label class="block mb-1">Precio (€)</label>
      <input
        type="number"
        name="price"
        value="{{ old('price') }}"
        step="0.01"
        min="0"
        class="form-input w-full"
        required
      />
    </div>

    {{-- Stock --}}
    <div>
      <label class="block mb-1">Stock</label>
      <input
        type="number"
        name="stock"
        value="{{ old('stock') }}"
        min="0"
        class="form-input w-full"
        required
      />
    </div>

    {{-- Descripción --}}
    <div>
      <label class="block mb-1">Descripción</label>
      <textarea
        name="description"
        class="form-textarea w-full"
        rows="4"
      >{{ old('description') }}</textarea>
    </div>

    {{-- Foto --}}
    <div>
      <label class="block mb-1">Foto (opcional)</label>
      <input
        type="file"
        name="photo"
        accept="image/*"
        class="form-input w-full"
      />
    </div>

    {{-- Límites de stock --}}
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block mb-1">Límite inf.</label>
        <input
          type="number"
          name="stock_lower_limit"
          value="{{ old('stock_lower_limit') }}"
          min="0"
          class="form-input w-full"
        />
      </div>
      <div>
        <label class="block mb-1">Límite sup.</label>
        <input
          type="number"
          name="stock_upper_limit"
          value="{{ old('stock_upper_limit') }}"
          min="0"
          class="form-input w-full"
        />
      </div>
    </div>

    {{-- Descuento por cantidad --}}
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block mb-1">Qty mín para descuento</label>
        <input
          type="number"
          name="discount_min_qty"
          value="{{ old('discount_min_qty') }}"
          min="0"
          class="form-input w-full"
        />
      </div>
      <div>
        <label class="block mb-1">Descuento (%)</label>
        <input
          type="number"
          name="discount"
          value="{{ old('discount') }}"
          step="0.01"
          min="0"
          class="form-input w-full"
        />
      </div>
    </div>

    <button type="submit" class="btn">Guardar Producto</button>
  </form>
</x-layouts.main-content>