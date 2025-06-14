<x-layouts.main-content title="Editar Usuario">
  <form 
    action="{{ route('users.update', $user) }}" 
    method="POST" 
    enctype="multipart/form-data" 
    class="max-w-lg space-y-6"
  >
    @csrf
    @method('PUT')

    {{-- Nombre completo --}}
    <div>
      <label class="block mb-1 font-semibold">Nombre</label>
      <input 
        type="text" 
        name="name" 
        value="{{ old('name', $user->name) }}" 
        class="form-input w-full" 
        required
      >
      @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Email --}}
    <div>
      <label class="block mb-1 font-semibold">Email</label>
      <input 
        type="email" 
        name="email" 
        value="{{ old('email', $user->email) }}" 
        class="form-input w-full" 
        required
      >
      @error('email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Género --}}
    <div>
      <label class="block mb-1 font-semibold">Género</label>
      <select name="gender" class="form-select w-full" required>
        <option value="male"   {{ old('gender', $user->gender)=='male'   ? 'selected' : '' }}>Masculino</option>
        <option value="female" {{ old('gender', $user->gender)=='female' ? 'selected' : '' }}>Femenino</option>
        <option value="other"  {{ old('gender', $user->gender)=='other'  ? 'selected' : '' }}>Otro</option>
      </select>
      @error('gender')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Campos solo para socios y board --}}
    @if($user->type !== 'employee')
      {{-- Dirección de entrega --}}
      <div>
        <label class="block mb-1 font-semibold">Dirección de entrega</label>
        <input 
          type="text" 
          name="delivery_address" 
          value="{{ old('delivery_address', $user->delivery_address) }}" 
          class="form-input w-full"
        >
        @error('delivery_address')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      {{-- NIF --}}
      <div>
        <label class="block mb-1 font-semibold">NIF</label>
        <input 
          type="text" 
          name="nif" 
          value="{{ old('nif', $user->nif) }}" 
          class="form-input w-full"
        >
        @error('nif')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>

      {{-- Información de pago --}}
      <div>
        <label class="block mb-1 font-semibold">Detalles de pago</label>
        <input 
          type="text" 
          name="payment_details" 
          value="{{ old('payment_details', $user->payment_details) }}" 
          class="form-input w-full"
        >
        @error('payment_details')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      </div>
    @endif

    {{-- Foto de perfil --}}
    <div>
      <label class="block mb-1 font-semibold">Foto de perfil (opcional)</label>
      <input 
        type="file" 
        name="photo" 
        accept="image/*" 
        class="form-input w-full"
      >
      @if($user->photo)
        <img 
          src="{{ $user->image_url }}" 
          alt="Foto de {{ $user->name }}" 
          class="h-24 w-24 object-cover rounded mt-2"
        >
      @endif
      @error('photo')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Cambio de contraseña --}}
    <div class="mt-4">
      <label class="block mb-1 font-semibold">Nueva contraseña (opcional)</label>
      <input 
        type="password" 
        name="password" 
        class="form-input w-full" 
        placeholder="Dejar en blanco para no cambiar"
      >
      @error('password')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block mb-1 font-semibold">Confirmar contraseña</label>
      <input 
        type="password" 
        name="password_confirmation" 
        class="form-input w-full" 
        placeholder="Repite la contraseña"
      >
    </div>

    <button type="submit" class="btn bg-blue-600 text-white">
      Guardar cambios
    </button>
  </form>
</x-layouts.main-content>