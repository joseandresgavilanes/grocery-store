<x-layouts.main-content title="Editar Categoría">
  <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="max-w-md">
    @csrf @method('PUT')

    <div class="mb-4">
      <label class="block mb-1">Nombre</label>
      <input
        type="text"
        name="name"
        value="{{ old('name', $category->name) }}"
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
      @if($category->image_url)
        <img src="{{ $category->image_url }}" class="h-16 w-16 mt-2 object-cover rounded" alt="Preview"/>
      @endif
    </div>

    <button class="btn">Actualizar</button>
  </form>
</x-layouts.main-content>