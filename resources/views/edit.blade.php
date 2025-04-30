<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($domain) ? 'Edit Domain' : 'Create Domain' }}
        </h2>
    </x-slot>

    <div class="w-[70%] mx-auto mt-[25px] p-6 bg-white rounded shadow">
        <form method="POST" action="{{ isset($domain) ? route('admin.update', $domain->id) : route('admin.store') }}" id="adminForm" class="space-y-4">
            @csrf
            @if(isset($domain))
                @method('PUT')
            @endif
            <label class="block font-medium text-gray-700">Domain </label>
            <input name="domain" value="{{ $domain->domain ?? '' }}" placeholder="Domain" required class="border p-2 rounded w-full" />
            <hr>

            <div id="pagesContainer">
                @if(isset($domain))
                    @foreach ($domain->domainPages as $page)
                        <label class="block font-medium text-gray-700">Page</label>
                        <input name="page[]" value="{{ $page->page }}" required class="  border p-2 rounded w-full" />
                        <input name="title[]" value="{{ $page->title }}" class="border p-2  mt-[15px] rounded w-full" required>
                        <input name="description[]" value="{{ $page->description }}" class="border p-2 mt-[15px] mb-[25px] rounded w-full" required>
                    @endforeach

                @endif
            </div>

            <button type="button" id="addFieldsButton" class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                Add Page
            </button>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full">
                {{ isset($domain) ? 'Update' : 'Create' }}
            </button>
        </form>
    </div>

    <script>
        let pageCounter = {{ isset($domain) ? count($domain->domainPages) + 1 : 1 }};

        document.getElementById('addFieldsButton').addEventListener('click', function () {
            const container = document.getElementById('pagesContainer');

            const label = document.createElement('label');
            label.textContent = `Page ${pageCounter}`;
            label.classList.add('block', 'font-bold', 'mt-4');

            const page = document.createElement('input');
            page.name = 'page[]';
            page.placeholder = 'Page';
            page.required = true;
            page.classList.add('border', 'p-2','rounded', 'w-full');

            const title = document.createElement('input');
            title.name = 'title[]';
            title.placeholder = 'Title';
            title.required = true;
            title.classList.add('border', 'p-2','mt-[15px]','mb-[15px]', 'rounded', 'w-full');

            const desc = document.createElement('input');
            desc.name = 'description[]';
            desc.placeholder = 'Description';
            desc.required = true;
            desc.classList.add('border', 'p-2','mb-[15px]', 'rounded', 'w-full');

            container.appendChild(label);
            container.appendChild(page);
            container.appendChild(title);
            container.appendChild(desc);

            pageCounter++;
        });
    </script>
</x-app-layout>
