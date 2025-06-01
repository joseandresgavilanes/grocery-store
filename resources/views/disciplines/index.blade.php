<x-layouts.main-content title="Disciplines"
                        heading="List of disciplines"
                        subheading="Manage the disciplines offered by the institution">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl ">
        <div class="flex justify-start ">
            <div class="my-4 p-6 ">
                <div class="flex items-center gap-4 mb-4">
                    <flux:button variant="primary" href="{{ route('disciplines.create') }}">Create a new discipline</flux:button>
                </div>
                <div class="my-4 font-base text-sm text-gray-700 dark:text-gray-300">
                    <table class="table-auto border-collapse">
                        <thead>
                        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                            <th class="px-2 py-2 text-left">Abbreviation</th>
                            <th class="px-2 py-2 text-left">Name</th>
                            <th class="px-2 py-2 text-left hidden sm:table-cell">Course</th>
                            <th class="px-2 py-2 text-left hidden sm:table-cell">Year</th>
                            <th class="px-2 py-2 text-left hidden sm:table-cell">Semester</th>
                            <th class="px-2 py-2 text-left hidden sm:table-cell">ECTS</th>
                            <th class="px-2 py-2 text-left hidden sm:table-cell">Hours</th>
                            <th class="px-2 py-2 text-left hidden sm:table-cell">Optional</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($disciplines as $discipline)
                            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                                <td class="px-2 py-2 text-left">{{ $discipline->abbreviation }}</td>
                                <td class="px-2 py-2 text-left">{{ $discipline->name }}</td>
                                <td class="px-2 py-2 text-left hidden sm:table-cell">{{ $discipline->course }}</td>
                                <td class="px-2 py-2 text-left hidden sm:table-cell">{{ $discipline->year }}</td>
                                <td class="px-2 py-2 text-left hidden sm:table-cell">{{ $discipline->semesterDescription }}</td>
                                <td class="px-2 py-2 text-left hidden sm:table-cell">{{ $discipline->ECTS }}</td>
                                <td class="px-2 py-2 text-left hidden sm:table-cell">{{ $discipline->hours }}</td>
                                <td class="px-2 py-2 text-left hidden sm:table-cell">{{ $discipline->optional ? 'optional' : '' }}</td>
                                <td class="ps-2 px-0.5">
                                    <a href="{{ route('disciplines.show', ['discipline' => $discipline]) }}">
                                        <flux:icon.eye class="size-5 hover:text-gray-600" />
                                    </a>
                                </td>
                                <td class="px-0.5">
                                    <a href="{{ route('disciplines.edit', ['discipline' => $discipline]) }}">
                                        <flux:icon.pencil-square class="size-5 hover:text-blue-600" />
                                    </a>
                                </td>
                                <td class="px-0.5">
                                    <form method="POST" action="{{ route('disciplines.destroy', ['discipline' => $discipline]) }}" class="flex items-center">
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
                    {{ $disciplines->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.main-content>
