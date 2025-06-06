<x-layouts.main-content :title="'My Shopping Card'" :heading="'My Shopping Card'">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="mt-6 space-y-4">
                    @if (!empty($cart) && count($cart) > 0)
                        @each('cart.partials.cards', $cart, 'product')
                    @else
                        <p class="text-gray-500">Your cart is empty.</p>
                    @endif
                </div>
                </form>
            </section>
        </div>
    </div>
</x-layouts.main-content>
