<x-layouts.main-content title="List of products">
    <div class="flex flex-col">
        @each('products.partials.cards', $products, 'product')
    </div>
</x-layouts.main-content>
