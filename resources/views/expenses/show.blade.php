<x-app-layout>
    @php($breadcrumbs = ['Expenses' => route('expenses.index'), $expense->title => null])
    <x-page-header :title="$expense->title" subtitle="Expense details.">
        <x-slot name="actions">
            <a href="{{ route('expenses.edit', $expense) }}" class="btn-secondary">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
            <a href="{{ route('expenses.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-border pb-3">
            <span class="text-sm text-muted">Amount</span>
            <span class="text-2xl font-bold text-ink">₹{{ number_format($expense->amount, 2) }}</span>
        </div>

        <div class="flex items-center justify-between border-b border-border pb-3">
            <span class="text-sm text-muted">Category</span>
            <x-badge color="info">{{ ucfirst($expense->category) }}</x-badge>
        </div>

        <div class="flex items-center justify-between border-b border-border pb-3">
            <span class="text-sm text-muted">Expense Date</span>
            <span class="font-medium text-ink">{{ $expense->expense_date->format('d M Y') }}</span>
        </div>

        @if ($expense->payment_method)
            <div class="flex items-center justify-between border-b border-border pb-3">
                <span class="text-sm text-muted">Payment Method</span>
                <span class="font-medium text-ink">{{ $expense->payment_method }}</span>
            </div>
        @endif

        @if ($expense->reference_no)
            <div class="flex items-center justify-between border-b border-border pb-3">
                <span class="text-sm text-muted">Reference No</span>
                <span class="font-medium text-ink">{{ $expense->reference_no }}</span>
            </div>
        @endif

        <div class="flex items-center justify-between border-b border-border pb-3">
            <span class="text-sm text-muted">Recorded By</span>
            <span class="font-medium text-ink">{{ $expense->user->name ?? '—' }}</span>
        </div>

        @if ($expense->description)
            <div>
                <span class="text-sm text-muted">Description</span>
                <p class="mt-1 text-sm text-ink">{{ $expense->description }}</p>
            </div>
        @endif

        <div class="pt-2 flex justify-end">
            <x-delete-form :action="route('expenses.destroy', $expense)" :label="$expense->title" />
        </div>
    </div>
</x-app-layout>
