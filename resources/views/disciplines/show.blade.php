<x-layouts.main-content :title="$discipline->name"
                        :heading="'Discipline '. $discipline->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                    <div class="mt-6 space-y-4">
                        @include('disciplines.partials.fields', ['mode' => 'show'])
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-layouts.main-content>
