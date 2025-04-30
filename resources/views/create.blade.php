<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Panel') }}
        </h2>
    </x-slot>

    <div class="w-[70%] mx-auto mt-[25px] p-6 bg-white shadow-[0_0_10px_rgba(255,255,255,0.5)] rounded">
        <form method="POST" action="{{ route('admin.store') }}" class="space-y-4" id="adminForm">
            @csrf

            <input name="domain" placeholder="Domain" required class="border p-2 rounded w-full" />

            <!-- Add Additional Info Button -->
            <button type="button" id="addFieldsButton" class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                Add Modal page
            </button>

            <!-- Submit Button -->
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full">
                Create
            </button>
        </form>
    </div>

    <script>
        let pageCounter = 1; // Initialize the page counter

        // Function to add the additional fields dynamically
        document.getElementById('addFieldsButton').addEventListener('click', function() {
            // Create the label
            const label = document.createElement('label');
            label.textContent = `Page ${pageCounter}`;
            label.classList.add('block', 'font-medium', 'text-gray-700'); // Add styling for the label

            // Create the new input elements
            const pageInput = document.createElement('input');
            pageInput.name = 'page[]';
            pageInput.placeholder = 'Page';
            pageInput.required = true;
            pageInput.classList.add('border', 'p-2', 'rounded', 'w-full');

            const titleInput = document.createElement('input');
            titleInput.name = 'title[]';
            titleInput.placeholder = 'Title';
            titleInput.required = true;
            titleInput.classList.add('border', 'p-2', 'rounded', 'w-full');

            const descriptionInput = document.createElement('input');
            descriptionInput.name = 'description[]';
            descriptionInput.placeholder = 'Description';
            descriptionInput.required = true;
            descriptionInput.classList.add('border', 'p-2', 'rounded', 'w-full');

            // Add the label and inputs to the form
            const form = document.getElementById('adminForm');
            form.insertBefore(label, document.getElementById('addFieldsButton'));
            form.insertBefore(pageInput, document.getElementById('addFieldsButton'));
            form.insertBefore(titleInput, document.getElementById('addFieldsButton'));
            form.insertBefore(descriptionInput, document.getElementById('addFieldsButton'));

            // Increment the page counter for the next set of fields
            pageCounter++;
        });
    </script>
</x-app-layout>
