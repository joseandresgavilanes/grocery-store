<x-layouts.main-content title="Gestión de Usuarios">
  <div class="mb-4">
    <form action="{{ route('users.index') }}" method="GET" class="flex space-x-2">
      <select name="type" class="form-select">
        <option value="">Todos</option>
        <option value="employee" {{ request('type')=='employee'?'selected':'' }}>Empleados</option>
        <option value="member"   {{ request('type')=='member'  ?'selected':'' }}>Miembros</option>
        <option value="board"    {{ request('type')=='board'   ?'selected':'' }}>Board</option>
      </select>
      <button class="btn">Filtrar</button>
    </form>
  </div>

  <table class="w-full border">
    <thead>
      <tr>
        <th>Nombre</th><th>Email</th><th>Tipo</th><th>Estado</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $u)
      <tr>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->type }}</td>
        <td>{{ $u->blocked ? 'Bloqueado' : 'Activo' }}</td>
        <td class="space-x-1">
          @can('update',$u)
            <a href="{{ route('users.edit',$u) }}" class="btn-xs">Editar</a>
          @endcan

          @can('delete',$u)
            <form action="{{ route('users.destroy',$u) }}" method="POST" class="inline">
              @csrf @method('DELETE')
              <button class="btn-xs btn-red">Cancelar Membresía</button>
            </form>
          @endcan

          @can('block',$u)
            <form action="{{ route($u->blocked?'users.unblock':'users.block',$u) }}" method="POST" class="inline">
              @csrf
              <button class="btn-xs">
                {{ $u->blocked ? 'Desbloquear' : 'Bloquear' }}
              </button>
            </form>
          @endcan

          @can('promote',$u)
            <form action="{{ route('users.promote',$u) }}" method="POST" class="inline">
              @csrf
              <button class="btn-xs">Promover</button>
            </form>
          @endcan

          @can('demote',$u)
            <form action="{{ route('users.demote',$u) }}" method="POST" class="inline">
              @csrf
              <button class="btn-xs">Revocar</button>
            </form>
          @endcan
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $users->withQueryString()->links() }}
</x-layouts.main-content>