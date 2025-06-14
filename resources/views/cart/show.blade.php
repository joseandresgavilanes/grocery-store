<x-layouts.main-content :title="'Mi Carrito'" :heading="'Mi Carrito'">
    <div class="flex flex-col space-y-6">
        @if (count($items) > 0)
            <div class="flex space-x-6">

                @php
                    $subtotal = 0;
                @endphp

                <table class="min-w-[60%] table-auto border border-gray-300 text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left">Producto</th>
                            <th class="px-4 py-2 text-left">Precio</th>
                            <th class="px-4 py-2 text-left">Cantidad</th>
                            <th class="px-4 py-2 text-left">Subtotal</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            @php 
                                $product = $item['product']; 
                                $itemSubtotal = $product->price * $item['quantity'];
                                $subtotal += $itemSubtotal;
                            @endphp
                            @include('cart.partials.cards', [
                                'product' => $product, 
                                'item' => $item, 
                                'itemSubtotal' => $itemSubtotal
                            ])
                        @endforeach
                    </tbody>
                </table>

                <div class="min-w-[35%] flex flex-col justify-start mt-6">
                    <table class="table-auto border border-gray-300 text-sm mb-4">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th colspan="2" class="px-4 py-2 text-left">Resumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="px-4 py-2 font-semibold">Subtotal:</td>
                                <td class="px-4 py-2">{{ number_format($subtotal, 2) }} $</td>
                            </tr>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="px-4 py-2 font-semibold">Gastos envío:</td>
                                <td class="px-4 py-2">{{ number_format($shipping, 2) }} $</td>
                            </tr>
                            <tr class="border-t border-gray-200 dark:border-gray-700 font-bold">
                                <td class="px-4 py-3">Total:</td>
                                <td class="px-4 py-3">{{ number_format($subtotal + $shipping, 2) }} $</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="space-y-2">
                        <form method="POST" action="{{ route('cart.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded w-full">Vaciar
                                Carrito</button>
                        </form>
                        <a href="{{ route('payment') }}"
                            class="block w-full text-center bg-green-600 hover:bg-green-700 text-white py-2 rounded">
                            Proceder al Pago
                        </a>
                    </div>
                </div>
            </div>
        @else
            <p class="text-gray-500">El carrito está vacío.</p>
        @endif
    </div>
</x-layouts.main-content>
