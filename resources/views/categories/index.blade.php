<x-layouts.main-content title="Categorías">
  <a href="{{ route('categories.create') }}" class="btn mb-4">+ Nueva Categoría</a>

  <table class="w-full border-collapse border border-gray-200">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2 border">Nombre</th>
        <th class="p-2 border">Imagen</th>
        <th class="p-2 border">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($categories as $cat)
      <tr>
        <td class="p-2 border">{{ $cat->name }}</td>
        <td class="p-2 border">
          <img src="{{ $cat->image_url }}" class="h-12 w-12 object-cover rounded" alt="{{ $cat->name }}"/>
        </td>
        <td class="p-2 border space-x-2">
          @can('update', $cat)
            <a href="{{ route('categories.edit', $cat) }}" class="btn-xs">Editar</a>
          @endcan
          @can('delete', $cat)
            <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="inline">
              @csrf @method('DELETE')
              <button class="btn-xs btn-red">Eliminar</button>
            </form>
          @endcan
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $categories->links() }}
</x-layouts.main-content>