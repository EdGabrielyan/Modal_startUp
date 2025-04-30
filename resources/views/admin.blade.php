<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Panel') }}
        </h2>
    </x-slot>

    <div class="p-4">
        <form method="POST" action="{{ route('admin.store') }}" class="mb-6">
            @csrf
            <div class="flex gap-4">
                <input name="domain" placeholder="Domain" required class="border p-2 rounded w-1/4" />
                <input name="page" placeholder="Page" required class="border p-2 rounded w-1/4" />
                <input name="title" placeholder="title" required class="border p-2 rounded w-1/4" />
                <input name="description" placeholder="description" required class="border p-2 rounded w-1/4" />
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Create</button>

            </div>
        </form>

        <table  class="min-w-full border divide-y divide-gray-300">
            <thead>
            <tr class="bg-gray-100">
                <th class="p-2 text-left">Domain</th>
                <th class="p-2 text-left">Page</th>
                <th class="p-2 text-left">Script</th>
                <th class="p-2 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            @if ($domainPages)
                @foreach($domainPages as $dp)
                    <tr style="color:white;" class="border-t">
                        <td class="p-2">{{ $dp->domain }}</td>
                        <td
                                class="p-2">{{ $dp->page }}</td>
                        <td style="color: white;" class="p-2 text-sm text-gray-700">
                            {!! htmlentities('<script src="http://127.0.0.1:8000/widget.js?script='.$dp->script.'"></script>') !!}
                        </td>
                        <td class="p-2 flex gap-2">
                            <!-- Update Form -->
                            <form method="POST" action="{{ route('admin.update', $dp->id) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="domain" value="{{ $dp->domain }}">
                                <input type="hidden" name="page" value="{{ $dp->page }}">
                                <input type="hidden" name="script" value="{{ $dp->script }}">
                                <button class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Update</button>
                            </form>

                            <!-- Delete Form -->
                            <form method="POST" action="{{ route('admin.destroy', $dp->id) }}">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 text-white px-2 py-1 rounded text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</x-app-layout>
