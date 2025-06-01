<x-layouts.main-content title="Departments"
                        heading="List of departments"
                        subheading="Manage the departments of the institution">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl ">
        <div class="flex justify-start ">
            <div class="my-4 p-6 ">
                <div class="flex items-center gap-4 mb-4">
                    <flux:button variant="primary" href="{{ route('departments.create') }}">Create a new department</flux:button>
                </div>
                <div class="my-4 font-base text-sm text-gray-700 dark:text-gray-300">
                    <table class="table-auto border-collapse">
                        <thead>
                        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                            <th class="px-2 py-2 text-left">Abbreviation</th>
                            <th class="px-2 py-2 text-left">Name</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($departments as $department)
                            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                                <td class="px-2 py-2 text-left">{{ $department->abbreviation }}</td>
                                <td class="px-2 py-2 text-left">{{ $department->name }}</td>
                                <td class="ps-2 px-0.5">
                                    <a href="{{ route('departments.show', ['department' => $department]) }}">
                                        <flux:icon.eye class="size-5 hover:text-gray-600" />
                                    </a>
                                </td>
                                <td class="px-0.5">
                                    <a href="{{ route('departments.edit', ['department' => $department]) }}">
                                        <flux:icon.pencil-square class="size-5 hover:text-blue-600" />
                                    </a>
                                </td>
                                <td class="px-0.5">
                                    <form method="POST" action="{{ route('departments.destroy', ['department' => $department]) }}" class="flex items-center">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">
                                            <flux:icon.trash class="size-5 hover:text-red-600" />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $departments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.main-content>
