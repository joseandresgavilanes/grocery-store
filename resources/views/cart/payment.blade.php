{{-- resources/views/cart/payment.blade.php --}}
<x-layouts.main-content :title="'Payment'" :heading="'Payment'">
  @php $user = auth()->user(); @endphp

  <form method="POST" action="{{ route('cart.checkout') }}" class="max-w-3xl mx-auto mt-10 space-y-6">
    @csrf

    {{-- Billing Information --}}
    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg p-6 space-y-4">
      <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Billing Information</h2>
      <div>
        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Client</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          {{-- Name & Email (solo lectura) --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
            <input type="text" value="{{ $user->name }}" readonly
                   class="mt-1 block w-full border rounded p-2 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-100">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input type="text" value="{{ $user->email }}" readonly
                   class="mt-1 block w-full border rounded p-2 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-100">
          </div>

          {{-- Delivery Address --}}
          <div>
            <label for="delivery_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
            <input id="delivery_address" name="delivery_address" type="text" required
                   value="{{ old('delivery_address', $user->default_delivery_address) }}"
                   class="mt-1 block w-full border rounded p-2 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-100">
            @error('delivery_address') 
              <p class="text-red-600 text-sm">{{ $message }}</p> 
            @enderror
          </div>

          {{-- NIF --}}
          <div>
            <label for="nif" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIF</label>
            <input id="nif" name="nif" type="text" required
                   value="{{ old('nif', $user->nif) }}"
                   class="mt-1 block w-full border rounded p-2 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-100">
            @error('nif') 
              <p class="text-red-600 text-sm">{{ $message }}</p> 
            @enderror
          </div>
        </div>
      </div>
    </div>

    {{-- Order Summary --}}
    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg p-6 space-y-4">
      <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Order Summary</h3>
      <ul class="divide-y divide-gray-200 dark:divide-gray-700">
        <li class="py-2 flex justify-between text-gray-700 dark:text-gray-200">
          <span>Subtotal</span>
          <span>{{ number_format($subtotal, 2) }}€</span>
        </li>
        <li class="py-2 flex justify-between font-bold text-gray-700 dark:text-gray-300">
          <span>Shipping</span>
          <span>{{ number_format($shipping, 2) }}€</span>
        </li>
        <li class="py-2 flex justify-between text-lg font-bold text-gray-900 dark:text-white">
          <span>Total</span>
          <span>{{ number_format($total, 2) }}€</span>
        </li>
      </ul>
    </div>

    {{-- Payment Method --}}
    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg p-6 space-y-4">
      <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Payment Method</h3>
      <div class="bg-gray-100 dark:bg-gray-800 border p-4 rounded space-y-3">
        <label class="flex items-center">
          <input type="radio" name="payment_method" value="virtual" checked>
          <span class="ml-2">Virtual Card (Balance: {{ number_format($user->card->balance,2) }}€)</span>
        </label>
        <label class="flex items-center">
          <input type="radio" name="payment_method" value="Visa">
          <span class="ml-2">Visa</span>
        </label>
        <label class="flex items-center">
          <input type="radio" name="payment_method" value="PayPal">
          <span class="ml-2">PayPal</span>
        </label>
        <label class="flex items-center">
          <input type="radio" name="payment_method" value="MBWAY">
          <span class="ml-2">MB WAY</span>
        </label>
      </div>
      @error('payment_method') 
        <p class="text-red-600 text-sm">{{ $message }}</p> 
      @enderror

      {{-- Campos condicionales --}}
      <div id="fields-virtual" class="hidden mt-4">
        {{-- Ningún campo extra --}}
      </div>

      <div id="fields-Visa" class="hidden mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Card Number</label>
          <input name="visa_card_number" type="text" value="{{ old('visa_card_number') }}" class="form-input w-full">
          @error('visa_card_number') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium">CVC</label>
          <input name="visa_cvc" type="text" value="{{ old('visa_cvc') }}" class="form-input w-full">
          @error('visa_cvc') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
      </div>

      <div id="fields-PayPal" class="hidden mt-4">
        <label class="block text-sm font-medium">PayPal Email</label>
        <input name="paypal_email" type="email" value="{{ old('paypal_email') }}" class="form-input w-full">
        @error('paypal_email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
      </div>

      <div id="fields-MBWAY" class="hidden mt-4">
        <label class="block text-sm font-medium">MB WAY Phone</label>
        <input name="mbway_phone" type="text" value="{{ old('mbway_phone') }}" class="form-input w-full">
        @error('mbway_phone') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
      </div>
    </div>

    {{-- Submit --}}
    <div class="text-center">
      <button type="submit"
        class="w-full bg-gray-700 hover:bg-gray-800 dark:bg-gray-600 dark:hover:bg-gray-700 text-white py-2 rounded text-lg">
        Place Order
      </button>
    </div>
  </form>

  {{-- JavaScript puro para alternar los campos --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const radios = document.querySelectorAll('input[name="payment_method"]');
      const groups = {
        virtual: document.getElementById('fields-virtual'),
        Visa:    document.getElementById('fields-Visa'),
        PayPal:  document.getElementById('fields-PayPal'),
        MBWAY:   document.getElementById('fields-MBWAY'),
      };
      function toggle() {
        radios.forEach(r => {
          const g = groups[r.value];
          if (!g) return;
          g.style.display = r.checked ? 'block' : 'none';
        });
      }
      radios.forEach(r => r.addEventListener('change', toggle));
      toggle();
    });
  </script>
</x-layouts.main-content>