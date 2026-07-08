<x-app-layout>
    @php($breadcrumbs = ['Expenses' => route('expenses.index'), 'Add Expense' => null])
    <x-page-header title="Add Expense" subtitle="Record a new business expense." />

    <div class="card p-6 max-w-3xl">
        <form method="POST" action="{{ route('expenses.store') }}">
            @include('expenses._form')
        </form>
    </div>
</x-app-layout>
