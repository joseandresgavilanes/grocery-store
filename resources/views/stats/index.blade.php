<x-layouts.main-content title="Catálogo de Productos">
    <div class="max-w-6xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Estadísticas de ventas</h1>

        <div class="bg-white p-6 rounded shadow mb-6">
            <form method="GET" action="{{ route('stats.index') }}">
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block mb-1 text-sm font-semibold">Producto</label>
                        <select name="product_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- Selecciona un producto --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ $selectedProduct == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-semibold">Desde</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="border rounded px-3 py-2 w-full">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-semibold">Hasta</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="border rounded px-3 py-2 w-full">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-semibold">Tipo de dato</label>
                        <select name="filter_type" class="w-full border rounded px-3 py-2">
                            <option value="units" {{ $filterType === 'units' ? 'selected' : '' }}>Unidades</option>
                            <option value="amount" {{ $filterType === 'amount' ? 'selected' : '' }}>Monto total</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Filtrar
                        </button>
                    </div>
                </div>
            </form>

            @if($selectedProduct && $startDate && $endDate)
                <form method="GET" action="{{ route('stats.export') }}" class="mt-4">
                    <input type="hidden" name="product_id" value="{{ $selectedProduct }}">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="hidden" name="filter_type" value="{{ $filterType }}">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Exportar a Excel
                    </button>
                </form>
            @endif
        </div>

        @if($salesChart)
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">
                    {{ $filterType === 'amount' ? 'Monto total vendido por día ($)' : 'Unidades vendidas por día' }}
                </h2>
                {!! $salesChart->container() !!}
                <script src="{{ $salesChart->cdn() }}"></script>
                {{ $salesChart->script() }}
            </div>
        @else
            <div class="text-yellow-700 bg-yellow-100 border border-yellow-400 p-4 rounded">
                No hay datos disponibles para el filtro actual.
            </div>
        @endif
    </div>
</x-layouts.main-content>
