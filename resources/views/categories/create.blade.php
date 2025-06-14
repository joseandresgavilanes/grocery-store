<x-layouts.main-content title="Nueva Categoría">
  <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" class="max-w-md">
    @csrf

    <div class="mb-4">
      <label class="block mb-1">Nombre</label>
      <input
        type="text"
        name="name"
        value="{{ old('name') }}"
        class="form-input w-full"
        required
      />
    </div>

    <div class="mb-4">
      <label class="block mb-1">Imagen (opcional)</label>
      <input
        type="file"
        name="image"
        accept="image/*"
        class="form-input w-full"
      />
    </div>

    <button class="btn">Crear</button>
  </form>
</x-layouts.main-content>