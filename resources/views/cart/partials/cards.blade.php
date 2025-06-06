<div>
    <figure class="h-auto md:h-72 flex flex-col md:flex-row
                    rounded-none sm:rounded-xl
                    bg-zinc-50  dark:bg-gray-900
                    border border-zinc-200
                    my-4 p-8 md:p-0">
            <img class="h-full aspect-auto mx-auto rounded-full
                        md:rounded-l-xl md:rounded-r-none" src="{{ $product->image_url }}">
        <div class="h-auto p-6 text-center md:text-left space-y-1 flex flex-col">
                {{ $product->name }}
            <figcaption class="font-medium">
                <div class="flex justify-center md:justify-start font-base
                            text-base space-x-6 text-gray-700 dark:text-gray-300">
                    <div>{{ $product->price }} $</div>
                </div>
            </figcaption>
            <p class="pt-4 font-light text-gray-700 dark:text-gray-300 overflow-y-auto">
                {{ $product->description }}
            </p>
        </div>
    </figure>
</div>
