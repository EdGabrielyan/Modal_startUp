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

            <!-- Контактные поля -->
            <div class="space-y-3 mt-4 p-4 border rounded bg-gray-50">
                <h3 class="font-bold text-lg mb-2">Contact Methods</h3>

                @foreach (['telegram', 'email'] as $method)
                    <div class="flex flex-col space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="contacts[]" value="{{ $method }}" id="contact_{{ $method }}">
                            <label for="contact_{{ $method }}">{{ ucfirst($method) }}</label>
                        </div>
                        <input
                            type="text"
                            name="{{ $method }}_value"
                            placeholder="Enter {{ $method }}..."
                            class="hidden border p-2 rounded w-full"
                            id="input_{{ $method }}"
                        >

                        {{-- Добавим ссылку для Telegram --}}
                        @if ($method === 'telegram')
                            <div id="telegram_instructions" class="hidden">
                                <a href="https://t.me/fravr_notify_bot" target="_blank"
                                   class="text-blue-600 underline">
                                    Click here to open our Telegram bot
                                </a>
                                <p class="text-sm text-gray-500">Then click "Start" to link your Telegram</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

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

            const wrapper = document.createElement('div');
            wrapper.classList.add('space-y-3', 'mt-4', 'p-4', 'border', 'rounded', 'bg-gray-50');

            const sectionTitle = document.createElement('h3');
            sectionTitle.textContent = `Modal Page ${pageCounter}`;
            sectionTitle.classList.add('font-bold', 'text-lg', 'mb-2');
            wrapper.appendChild(sectionTitle);

            ['page', 'title', 'description'].forEach(field => {
                const input = document.createElement('input');
                input.name = `${field}[]`;
                input.placeholder = field.charAt(0).toUpperCase() + field.slice(1);
                input.required = true;
                input.classList.add('border', 'p-2', 'rounded', 'w-full');
                wrapper.appendChild(input);
            });

            form.insertBefore(wrapper, document.getElementById('addFieldsButton'));

            pageCounter++;
        });

        // Показать/скрыть input при клике на checkbox (email / telegram)
        ['telegram', 'email'].forEach(method => {
            const checkbox = document.getElementById(`contact_${method}`);
            const input = document.getElementById(`input_${method}`);
            const telegramInstructions = document.getElementById(`telegram_instructions`);

            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    input.classList.remove('hidden');

                    if (method === 'telegram' && telegramInstructions) {
                        telegramInstructions.classList.remove('hidden');
                    }
                } else {
                    input.classList.add('hidden');
                    input.value = '';

                    if (method === 'telegram' && telegramInstructions) {
                        telegramInstructions.classList.add('hidden');
                    }
                }
            });
        });
    </script>
</x-app-layout>
