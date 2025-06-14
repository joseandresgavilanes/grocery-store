<x-layouts.main-content title="Inventario">
    <div class="mb-6">
        <div class="flex gap-3">
            <a href="{{ route('inventory.index', ['filter' => 'out_of_stock']) }}"
                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                Sin stock
            </a>
            <a href="{{ route('inventory.index', ['filter' => 'low_stock']) }}"
                class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-sm">
                Bajo límite
            </a>
            <a href="{{ route('inventory.index') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm">
                Todos
            </a>
        </div>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full table-auto border border-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left border-b">Producto</th>
                    <th class="px-4 py-2 text-left border-b">Stock</th>
                    <th class="px-4 py-2 text-left border-b">Límite</th>
                    <th class="px-4 py-2 text-left border-b">Ajustar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $p->name }}</td>
                        <td class="px-4 py-2">{{ $p->stock }}</td>
                        <td class="px-4 py-2">{{ $p->stock_lower_limit }} – {{ $p->stock_upper_limit }}</td>
                        <td class="px-4 py-2">
                            <form action="{{ route('inventory.adjust', $p) }}" method="POST" class="flex flex-col gap-1">
                                @csrf

                                @if ($errors->has('new_stock') && old('product_id') == $p->id)
                                    <div class="text-red-600 text-xs">
                                        {{ $errors->first('new_stock') }}
                                    </div>
                                @endif

                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                                    <input type="number" name="new_stock"
                                        value="{{ old('product_id') == $p->id ? old('new_stock') : $p->stock }}"
                                        max="{{ $p->stock_upper_limit }}"
                                        class="w-20 px-2 py-1 border rounded text-sm">
                                    <button type="submit"
                                        class="px-2 py-1 text-white bg-blue-600 hover:bg-blue-700 rounded text-xs">
                                        OK
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</x-layouts.main-content>
