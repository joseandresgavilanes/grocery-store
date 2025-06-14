<x-layouts.main-content title="Gestión de Usuarios">
  <div class="mb-6">
    <form action="{{ route('users.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
      <select name="type" class="rounded-md border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Todos</option>
        <option value="employee" {{ request('type')=='employee'?'selected':'' }}>Empleados</option>
        <option value="member"   {{ request('type')=='member'  ?'selected':'' }}>Miembros</option>
        <option value="board"    {{ request('type')=='board'   ?'selected':'' }}>Board</option>
      </select>
      <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition">Filtrar</button>
    </form>
  </div>

  <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 text-sm">
      <thead class="bg-zinc-100 dark:bg-zinc-800 text-left text-zinc-700 dark:text-zinc-300 uppercase text-xs">
        <tr>
          <th class="px-4 py-3">Nombre</th>
          <th class="px-4 py-3">Email</th>
          <th class="px-4 py-3">Tipo</th>
          <th class="px-4 py-3">Estado</th>
          <th class="px-4 py-3">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
        @foreach($users as $u)
        <tr class="even:bg-zinc-50 dark:even:bg-zinc-900">
          <td class="px-4 py-2">{{ $u->name }}</td>
          <td class="px-4 py-2">{{ $u->email }}</td>
          <td class="px-4 py-2">{{ ucfirst($u->type) }}</td>
          <td class="px-4 py-2">
            @if($u->blocked)
              <span class="inline-block px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">Bloqueado</span>
            @else
              <span class="inline-block px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded">Activo</span>
            @endif
          </td>
          <td class="px-4 py-2 space-x-1 whitespace-nowrap">
            @can('update', $u)
              <a href="{{ route('users.edit', $u) }}" class="px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 transition">Editar</a>
            @endcan

            @can('delete', $u)
              <form action="{{ route('users.destroy', $u) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button class="px-2 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600 transition">Eliminar</button>
              </form>
            @endcan

            @can('block', $u)
              <form action="{{ route($u->blocked ? 'users.unblock' : 'users.block', $u) }}" method="POST" class="inline">
                @csrf
                <button class="px-2 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
                  {{ $u->blocked ? 'Desbloquear' : 'Bloquear' }}
                </button>
              </form>
            @endcan

            @can('promote', $u)
              <form action="{{ route('users.promote', $u) }}" method="POST" class="inline">
                @csrf
                <button class="px-2 py-1 text-xs bg-indigo-500 text-white rounded hover:bg-indigo-600 transition">Promover</button>
              </form>
            @endcan

            @can('demote', $u)
              <form action="{{ route('users.demote', $u) }}" method="POST" class="inline">
                @csrf
                <button class="px-2 py-1 text-xs bg-gray-500 text-white rounded hover:bg-gray-600 transition">Revocar</button>
              </form>
            @endcan
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $users->withQueryString()->links() }}
  </div>
</x-layouts.main-content>
