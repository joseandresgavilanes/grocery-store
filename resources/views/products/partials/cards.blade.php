@props(['product'])

{{-- 
    Container principal tipo “card”. 
    md:w-1/4 → en pantallas medianas hacia arriba, cada tarjeta ocupa 1/4 (para tener hasta 4 en una fila).
    w-full → en pantallas pequeñas ocupa todo el ancho (1 por fila).
--}}
<figure class="w-full p-4">
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow hover:shadow-lg transition duration-200 flex flex-col h-full">
        
        {{-- --- Imagen del producto (siempre centrada) --- --}}
        <div class="w-full flex items-center justify-center pt-4 px-4">
            {{-- 
                h-48 → altura fija para todas las imágenes, object-cover para preservar proporción 
                rounded-lg → esquinas redondeadas 
            --}}
            <img 
                src="{{ $product->image_url }}" 
                alt="Imagen de {{ $product->name }}" 
                class="h-48 w-full object-cover rounded-lg"
            >
        </div>

        {{-- --- Bloque de información: flex-col para que “crezca” y empuje el botón al final --- --}}
        <figcaption class="p-4 flex flex-col flex-1">
            
            {{-- 1) Nombre y categoría --}}
            <div class="mb-2">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $product->name }}
                </h2>
                <span class="text-sm italic text-gray-500 dark:text-gray-400">
                    ({{ $product->category->name }})
                </span>
            </div>
            
            {{-- 2) Precio y posible descuento --}}
            <div class="mb-2 flex items-center space-x-2">
                @if($product->discount && $product->discount_min_qty)
                    {{-- Precio tachado (antes) --}}
                    <span class="text-gray-400 dark:text-gray-600 line-through text-sm">
                        {{ number_format($product->price, 2, ',', '.') }} €
                    </span>
                    {{-- Precio con descuento --}}
                    @php
                        $precioDesc = $product->price * (1 - $product->discount);
                    @endphp
                    <span class="text-xl font-bold text-green-600 dark:text-green-400">
                        {{ number_format($precioDesc, 2, ',', '.') }} €
                    </span>
                    {{-- Badge de porcentaje --}}
                    <span class="ml-auto text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-1 rounded">
                        -{{ intval($product->discount * 100) }}%
                    </span>
                @else
                    {{-- Solo precio normal --}}
                    <span class="text-xl font-bold text-gray-800 dark:text-gray-200">
                        {{ number_format($product->price, 2, ',', '.') }} €
                    </span>
                @endif
            </div>

            {{-- 3) Puntuación (opcional) --}}
            @if(isset($product->rating))
                <div class="flex items-center mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->rating))
                            {{-- Estrella llena --}}
                            <svg class="w-4 h-4 text-yellow-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.49 6.91l6.56-.955L10 0l2.95 5.955 6.56.955-4.755 4.635 1.123 6.545z"/>
                            </svg>
                        @else
                            {{-- Estrella vacía o gris --}}
                            <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.49 6.91l6.56-.955L10 0l2.95 5.955 6.56.955-4.755 4.635 1.123 6.545z"/>
                            </svg>
                        @endif
                    @endfor
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                        ({{ $product->reviews_count ?? 0 }})
                    </span>
                </div>
            @endif

            {{-- 4) Descripción breve --}}
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 flex-1">
                {{ Str::limit($product->description, 100) }}
            </p>

            {{-- 6) Badge de Stock --}}
            @if($product->stock === 0)
                <span class="inline-block mb-2 px-3 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs font-semibold rounded-full">
                    Sin stock (llegada tardía posible)
                </span>
            @else
                <span class="inline-block mb-2 px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full">
                    En stock: {{ $product->stock }}
                </span>
            @endif

            {{-- 7) Formulario “Añadir al carrito” (se queda al final, con margin-top auto para empujar hasta abajo) --}}
            <form class="mt-auto pt-2 border-t border-gray-100 dark:border-gray-700">
                @csrf
                <div class="flex items-center space-x-2 mt-2">
                    {{-- Input de cantidad --}}
                    <div class="w-20">
                        <label for="quantity-{{ $product->id }}" class="sr-only">Cantidad</label>
                        <input 
                            id="quantity-{{ $product->id }}"
                            name="quantity" 
                            type="number" 
                            min="1" 
                            value="1" 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-center
                                   focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-900 dark:text-gray-200"
                        >
                    </div>
                    {{-- Botón añadir --}}
                    <button 
                        type="submit"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700
                               text-white font-medium rounded focus:outline-none focus:ring-2 focus:ring-blue-400
                               disabled:opacity-50"
                        @if($product->stock === 0) disabled @endif
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m4-9v9m4-9l2 9M9 21h6" />
                        </svg>
                        Añadir
                    </button>
                </div>
            </form>

        </figcaption>
    </div>
</figure>