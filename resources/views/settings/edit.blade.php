<x-layouts.main-content title="Membership Fee">
    <div class="max-w-3xl mx-auto mt-10 bg-white dark:bg-gray-900 shadow-md rounded-lg p-6 space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Membership Fee</h2>

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

        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="membership_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Membership Fee (€)
                </label>
                <input
                    type="number"
                    id="membership_fee"
                    name="membership_fee"
                    step="0.01"
                    min="0"
                    value="{{ old('membership_fee', $setting->membership_fee) }}"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500"
                />
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save
                </button>
            </div>
        </form>
    </div>
</x-layouts.main-content>
