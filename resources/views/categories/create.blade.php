<x-app-layout>
    <x-page-header title="New Category" subtitle="Create a new product category." />

    <div class="card p-6 max-w-2xl">
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" type="text" class="mt-1" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="4" class="form-textarea mt-1">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="flex items-center gap-3">
                <label for="status" class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" id="status" name="status" value="1" class="peer sr-only" {{ old('status', true) ? 'checked' : '' }}>
                    <div class="peer h-6 w-11 rounded-full bg-slate-200 transition-colors peer-checked:bg-primary-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-5"></div>
                </label>
                <x-input-label for="status" value="Active" class="mb-0" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                <a href="{{ route('categories.index') }}" class="btn-secondary">Cancel</a>
                <x-primary-button>
                    <i class="fa-solid fa-check"></i> Save Category
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
