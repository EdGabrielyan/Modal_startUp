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

            <!-- Кнопка добавления -->
            <button type="button" id="addFieldsButton" class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                Add Modal page
            </button>

            <!-- Submit -->
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full">
                Create
            </button>
        </form>
    </div>

    <script>
        let pageCounter = 1;

        document.getElementById('addFieldsButton').addEventListener('click', function () {
            const form = document.getElementById('adminForm');

            // Контейнер для одной секции
            const wrapper = document.createElement('div');
            wrapper.classList.add('space-y-3', 'mt-4', 'p-4', 'border', 'rounded', 'bg-gray-50');

            // Заголовок секции
            const sectionTitle = document.createElement('h3');
            sectionTitle.textContent = `Modal Page ${pageCounter}`;
            sectionTitle.classList.add('font-bold', 'text-lg', 'mb-2');
            wrapper.appendChild(sectionTitle);

            // Поля Page, Title, Description
            ['page', 'title', 'description'].forEach(field => {
                const input = document.createElement('input');
                input.name = `${field}[]`;
                input.placeholder = field.charAt(0).toUpperCase() + field.slice(1);
                input.required = true;
                input.classList.add('border', 'p-2', 'rounded', 'w-full');
                wrapper.appendChild(input);
            });

            // Контейнер для чекбоксов
            const methods = ['whatsapp', 'telegram', 'telephone', 'viber'];
            methods.forEach(method => {
                const checkboxContainer = document.createElement('div');
                checkboxContainer.classList.add('flex', 'items-center', 'space-x-2');

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = `contacts[${pageCounter}][]`;
                checkbox.value = method;
                checkbox.id = `${method}_${pageCounter}`;

                const label = document.createElement('label');
                label.htmlFor = checkbox.id;
                label.textContent = method.charAt(0).toUpperCase() + method.slice(1);

                const input = document.createElement('input');
                input.type = 'text';
                input.name = `${method}_value[${pageCounter}]`;
                input.placeholder = `Enter ${method}...`;
                input.classList.add('hidden', 'border', 'p-2', 'rounded', 'w-full');

                checkbox.addEventListener('change', () => {
                    if (checkbox.checked) {
                        input.classList.remove('hidden');
                    } else {
                        input.classList.add('hidden');
                        input.value = '';
                    }
                });

                checkboxContainer.appendChild(checkbox);
                checkboxContainer.appendChild(label);
                checkboxContainer.appendChild(input);

                wrapper.appendChild(checkboxContainer);
            });

            // Вставить перед кнопкой добавления
            form.insertBefore(wrapper, document.getElementById('addFieldsButton'));

            pageCounter++;
        });
    </script>
</x-app-layout>
