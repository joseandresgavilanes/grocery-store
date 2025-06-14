<x-layouts.main-content title="Cuota de Membresía">
  <form method="POST" action="{{ route('settings.update') }}" class="max-w-sm space-y-4">
    @csrf @method('PUT')

    <div>
      <label class="block mb-1">Cuota de membresía (€)</label>
      <input 
        type="number" 
        name="membership_fee" 
        step="0.01" 
        min="0" 
        value="{{ old('membership_fee', $setting->membership_fee) }}"
        class="form-input w-full" 
        required
      />
    </div>

    <button type="submit" class="btn">Guardar</button>
  </form>
</x-layouts.main-content>