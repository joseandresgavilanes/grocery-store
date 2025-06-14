@php

    $lowStock = $product->stock < $item['quantity'];
@endphp

<tr class="border-t border-gray-200 dark:border-gray-700 {{ $lowStock ? 'bg-yellow-100' : '' }}">

    <td class="px-4 py-2 flex items-center space-x-4">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded">
        <span>{{ $product->name }}</span>
        @if($lowStock)
            <span class="text-xs text-red-600">(stock bajo)</span>
        @endif
    </td>


    <td class="px-4 py-2 text-gray-800 dark:text-gray-100">
        {{ number_format($product->price, 2) }} $
    </td>


    <td class="px-4 py-2">
        <form method="POST" action="{{ route('cart.update', $product) }}" class="flex items-center space-x-1">
            @csrf
            @method('PATCH')
            <input type="number"
                   name="quantity"
                   value="{{ $item['quantity'] }}"
                   min="0"
                   class="w-16 border rounded px-1 py-0.5"
            >
            <button type="submit" class="px-2 py-1 bg-blue-500 text-white rounded">OK</button>
        </form>
    </td>


    <td class="px-4 py-2 text-gray-800 dark:text-gray-100">
        {{ number_format($product->price * $item['quantity'], 2) }} $
    </td>


    <td class="px-4 py-2">
        <form method="POST" action="{{ route('cart.remove', $product) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
        </form>
    </td>
</tr>
