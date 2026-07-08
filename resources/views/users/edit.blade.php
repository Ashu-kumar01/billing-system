<x-app-layout>
    <x-breadcrumb :items="['Users' => route('users.index'), 'Edit '.$user->name => null]" />

    <x-page-header title="Edit User" subtitle="Update user account details." />

    <div class="card p-6">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')
            @include('users._form')
        </form>
    </div>
</x-app-layout>
