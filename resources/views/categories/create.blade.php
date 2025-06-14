
@php
    $category = $category ?? null;
@endphp

<x-layouts.main-content title="New Category">
    <div class="max-w-3xl mx-auto mt-10 bg-white dark:bg-gray-900 shadow-md rounded-lg p-6 space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">New Category</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Errors:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">

                <!-- Fields for name and image -->
                <div class="w-full">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image (optional)</label>
                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300 file:bg-gray-100 dark:file:bg-gray-700 file:text-gray-700 dark:file:text-gray-200 file:border-0 file:rounded-md file:px-4 file:py-2 file:mr-4"
                        />
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Create
                </button>
            </div>
        </form>
    </div>
</x-layouts.main-content>
