@php
    $user = auth()->user();
@endphp

<x-layouts.main-content :title="'Edit'" :heading="'Edit'">
    <div class="max-w-3xl mx-auto mt-10 bg-white dark:bg-gray-900 shadow-md rounded-lg p-6 space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Edit Profile</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Errores:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="space-y-6" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- NIF -->
            <div>
                <label for="nif" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIF</label>
                <input type="text" id="nif" name="nif" value="{{ old('nif', $user->nif) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Address -->
            <div>
                <label for="default_delivery_address"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                <input type="text" id="default_delivery_address" name="default_delivery_address"
                    value="{{ old('default_delivery_address', $user->default_delivery_address) }}"
                    class="mt-1 block w-3/4 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Gender -->
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                <select id="gender" name="gender"
                    class="mt-1 block w-1/2 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select...</option>
                    <option value="F" {{ old('gender', $user->gender) === 'F' ? 'selected' : '' }}>Female</option>
                    <option value="M" {{ old('gender', $user->gender) === 'M' ? 'selected' : '' }}>Male</option>
                </select>
            </div>

            <!-- Payment Method -->

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment
                    Method</label>
                <div
                    class="bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 p-4 rounded space-y-3">

                    <div class="flex items-center">
                        <input id="mbway" type="radio" name="payment_method" value="MB WAY"
                            class="text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-blue-600">
                        <label for="mbway" class="ml-2 text-sm text-gray-800 dark:text-gray-200">MB WAY</label>
                    </div>

                    <div class="flex items-center">
                        <input id="visa" type="radio" name="payment_method" value="Visa"
                            class="text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-blue-600">
                        <label for="visa" class="ml-2 text-sm text-gray-800 dark:text-gray-200">Visa</label>
                    </div>

                    <div class="flex items-center">
                        <input id="paypal" type="radio" name="payment_method" value="PayPal"
                            class="text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-blue-600">
                        <label for="paypal" class="ml-2 text-sm text-gray-800 dark:text-gray-200">PayPal</label>
                    </div>

                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    New Password
                </label>
                <input type="password" id="password" name="password"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Confirm New Password
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
            </div>


            <!-- Profile Photo -->
            <div>
                <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile
                    Photo</label>
                <input type="file" id="profile_photo" name="photo"
                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300 file:bg-gray-100 dark:file:bg-gray-700 file:text-gray-700 dark:file:text-gray-200 file:border-0 file:rounded-md file:px-4 file:py-2 file:mr-4">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</x-layouts.main-content>
