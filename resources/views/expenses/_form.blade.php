@csrf

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
    <div>
        <x-input-label for="title" value="Title" />
        <x-text-input id="title" type="text" name="title" :value="old('title', $expense->title ?? '')" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="amount" value="Amount" />
        <x-text-input id="amount" type="number" step="0.01" min="0.01" name="amount" :value="old('amount', $expense->amount ?? '')" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="category" value="Category" />
        <select id="category" name="category" class="form-select">
            @foreach (\App\Models\Expense::CATEGORIES as $category)
                <option value="{{ $category }}" @selected(old('category', $expense->category ?? '') === $category)>{{ ucfirst($category) }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="expense_date" value="Expense Date" />
        <x-text-input id="expense_date" type="date" name="expense_date" :value="old('expense_date', isset($expense) ? $expense->expense_date->toDateString() : now()->toDateString())" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('expense_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="payment_method" value="Payment Method (optional)" />
        <x-text-input id="payment_method" type="text" name="payment_method" :value="old('payment_method', $expense->payment_method ?? '')" class="mt-1 block w-full" placeholder="e.g. Cash, UPI, Bank Transfer" />
        <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="reference_no" value="Reference No (optional)" />
        <x-text-input id="reference_no" type="text" name="reference_no" :value="old('reference_no', $expense->reference_no ?? '')" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('reference_no')" class="mt-2" />
    </div>
</div>

<div class="mt-6">
    <x-input-label for="description" value="Description (optional)" />
    <textarea id="description" name="description" rows="3" class="form-textarea">{{ old('description', $expense->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="btn-primary">
        <i class="fa-solid fa-check"></i> {{ isset($expense) ? 'Update Expense' : 'Save Expense' }}
    </button>
    <a href="{{ route('expenses.index') }}" class="btn-secondary">Cancel</a>
</div>
