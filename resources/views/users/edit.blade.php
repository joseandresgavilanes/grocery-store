<x-layouts.main-content title="Edit User">
    <div class="max-w-3xl mx-auto mt-10 bg-white dark:bg-gray-900 shadow-md rounded-lg p-6 space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Edit User</h2>

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

        <form action="{{ route('users.updateAdmin', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                <select id="gender" name="gender"
                    class="mt-1 block w-1/2 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select...</option>
                    <option value="F" {{ old('gender', $user->gender) === 'F' ? 'selected' : '' }}>Female</option>
                    <option value="M" {{ old('gender', $user->gender) === 'M' ? 'selected' : '' }}>Male</option>
                </select>
            </div>

            @if ($user->type !== 'employee')
                <div>
                    <label for="delivery_address"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Address</label>
                    <input type="text" id="delivery_address" name="delivery_address"
                        value="{{ old('delivery_address', $user->delivery_address) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('delivery_address')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nif" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIF</label>
                    <input type="text" id="nif" name="nif" value="{{ old('nif', $user->nif) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('nif')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


            @endif

            <div>
                <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Photo (optional)</label>
                <input type="file" id="photo" name="photo" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300 file:bg-gray-100 dark:file:bg-gray-700 file:text-gray-700 dark:file:text-gray-200 file:border-0 file:rounded-md file:px-4 file:py-2 file:mr-4">
                @if ($user->photo)
                    <img src="{{ $user->image_url }}" alt="{{ $user->name }}'s photo"
                        class="h-24 w-24 object-cover rounded mt-2">
                @endif
                @error('photo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password (optional)</label>
                <input type="password" id="password" name="password" placeholder="Leave blank to keep current password"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Repeat the password"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-layouts.main-content>
