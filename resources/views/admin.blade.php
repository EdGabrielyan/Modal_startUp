<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Panel') }}
        </h2>
    </x-slot>

    <div class="w-[70%] mx-auto p-4">
        <table class="min-w-full border divide-y divide-gray-300 overflow-hidden rounded-lg">
            <thead>
            <tr class="bg-gray-100">
                <th class="p-3 text-left rounded-tl-lg">Domain</th>
                <th class="p-3 text-left">Script</th>
                <th class="p-3 text-right rounded-tr-lg">
                    <a href="{{ route('admin.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500 transition">
                        Create
                    </a>
                </th>
            </tr>
            </thead>
            <tbody style="background-color: #2d3748">
            @if ($domainPages)
                @foreach($domainPages as $dp)
                    <tr class="border-t">
                        <td class="p-3 text-white bg-gray-800">{{ $dp->domain }}</td>
                        <td class="p-3 text-white bg-gray-700">
                            {!! htmlentities('<script src="http://127.0.0.1:80/widget.js?script='.$dp->script.'"></script>') !!}
                        </td>
                        <td class="p-3 flex gap-2 justify-end">
                            <!-- Update Form -->
                            <a href="{{ route('admin.edit', $dp->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-400 transition">Update</a>


                            <!-- Delete Form -->
                            <form method="POST" action="{{ route('admin.destroy', $dp->id) }}" class="inline-block">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-red-400 transition">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</x-app-layout>
