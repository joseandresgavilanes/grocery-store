<x-layouts.main-content title="New Course"
                        heading="Create a course"
                        subheading='Click on "Save" button to store the information.'>
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <form method="POST" action="{{ route('courses.store') }}">
                    @csrf
                    <div class="mt-6 space-y-4">
                        @include('courses.partials.fields', ['mode' => 'create'])
                    </div>
                    <div class="flex mt-6">
                        <flux:button variant="primary" type="submit"  class="uppercase">Save</flux:button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-layouts.main-content>
