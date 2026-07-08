<x-app-layout>
    <x-breadcrumb :items="['Users' => route('users.index'), 'Add User' => null]" />

    <x-page-header title="Add User" subtitle="Create a new system user." />

    <div class="card p-6">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            @include('users._form')
        </form>
    </div>
</x-app-layout>
