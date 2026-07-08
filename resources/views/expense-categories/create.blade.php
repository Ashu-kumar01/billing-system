<x-app-layout>
    @php($breadcrumbs = ['Expense Categories' => route('expense-categories.index'), 'New Category' => null])
    <x-page-header title="New Expense Category" subtitle="Create a new expense category." />

    <div class="card p-6 max-w-2xl">
        <form method="POST" action="{{ route('expense-categories.store') }}">
            @csrf

            <div>
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="description" value="Description (optional)" />
                <textarea id="description" name="description" rows="3" class="form-textarea">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="mt-6 flex items-center gap-2">
                <input type="checkbox" id="status" name="status" value="1" class="rounded border-border" @checked(old('status', true))>
                <x-input-label for="status" value="Active" />
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-check"></i> Save Category
                </button>
                <a href="{{ route('expense-categories.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
