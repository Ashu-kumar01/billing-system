<x-app-layout>
    @php($breadcrumbs = ['Expenses' => route('expenses.index'), 'Edit Expense' => null])
    <x-page-header title="Edit Expense" subtitle="Update this expense record." />

    <div class="card p-6 max-w-3xl">
        <form method="POST" action="{{ route('expenses.update', $expense) }}">
            @method('PUT')
            @include('expenses._form')
        </form>
    </div>
</x-app-layout>
