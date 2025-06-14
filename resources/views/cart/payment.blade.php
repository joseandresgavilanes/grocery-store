<x-layouts.main-content :title="'Payment'" :heading="'Payment'">
    @php
        $user = auth()->user();
    @endphp

    <div class="max-w-3xl mx-auto mt-10 bg-white dark:bg-gray-900 shadow-md rounded-lg p-6 space-y-6">

        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Billing Information</h2>

        <!-- Información del socio -->
        <div>
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Client</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input id="name" type="text"
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded p-2 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-100"
                        value="{{ $user->name }}" readonly>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input id="email" type="text"
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded p-2 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-100"
                        value="{{ $user->email }}" readonly>
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                    <input id="address" type="text"
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded p-2 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-100"
                        value="{{ $user->default_delivery_address ?? 'Not provided' }}" readonly>
                </div>
                <div>
                    <label for="nif" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIF</label>
                    <input id="nif" type="text"
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded p-2 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-100"
                        value="{{ $user->nif ?? 'Not provided' }}" readonly>
                </div>
            </div>
        </div>

        <!-- Resumen del pedido -->
        <div>
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

        <!-- Métodos de pago -->
        <div>
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Payment Method</h3>
            <div class="bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 p-4 rounded space-y-3">

                <div class="flex items-center">
                    <input id="mbway" type="radio" name="payment_method" value="MB WAY"
                        class="text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-blue-600">
                    <label for="mbway" class="ml-2 text-sm text-gray-800 dark:text-gray-200">MB WAY</label>
                </div>

                <div class="flex items-center">
                    <input id="visa" type="radio" name="payment_method" value="Visa"
                        class="text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-blue-600">
                    <label for="visa" class="ml-2 text-sm text-gray-800 dark:text-gray-200">Visa</label>
                </div>

                <div class="flex items-center">
                    <input id="paypal" type="radio" name="payment_method" value="PayPal"
                        class="text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-blue-600">
                    <label for="paypal" class="ml-2 text-sm text-gray-800 dark:text-gray-200">PayPal</label>
                </div>

            </div>
        </div>

        <!-- Botón para confirmar -->
        <form method="POST" action="{{ route('cart.checkout') }}">
            @csrf
            <button type="submit"
                class="w-full text-center bg-gray-700 hover:bg-gray-800 dark:bg-gray-600 dark:hover:bg-gray-700 text-white py-2 rounded text-lg">
                Place Order
            </button>
        </form>

    </div>
</x-layouts.main-content>
