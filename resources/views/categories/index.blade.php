<x-layouts.main-content title="Categorías">

  <div class="mb-6 flex justify-between items-center">
    <h1 class="text-xl font-semibold text-gray-800">Categorías</h1>
    <a href="{{ route('categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
      + Nueva Categoría
    </a>
  </div>

  <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
    <table class="min-w-full bg-white text-gray-700">
      <thead class="bg-gray-100 text-gray-600 uppercase text-sm font-medium">
        <tr>
          <th class="px-6 py-3 border-b border-gray-300 text-left">Nombre</th>
          <th class="px-6 py-3 border-b border-gray-300 text-left">Imagen</th>
          <th class="px-6 py-3 border-b border-gray-300 text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($categories as $cat)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 border-b border-gray-200">{{ $cat->name }}</td>
            <td class="px-6 py-4 border-b border-gray-200">
              <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="h-12 w-12 object-cover rounded-md">
            </td>
            <td class="px-6 py-4 border-b border-gray-200 text-center space-x-2">
              @can('update', $cat)
                <a href="{{ route('categories.edit', $cat) }}" 
                   class="inline-block px-3 py-1 text-xs font-semibold text-white bg-indigo-600 rounded hover:bg-indigo-700 transition">
                  Editar
                </a>
              @endcan
              @can('delete', $cat)
                <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="inline">
                  @csrf @method('DELETE')
                  <button type="submit"
                          class="inline-block px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 transition">
                    Eliminar
                  </button>
                </form>
              @endcan
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="3" class="px-6 py-4 text-center text-gray-500 italic">No hay categorías.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $categories->links() }}
  </div>

</x-layouts.main-content>
