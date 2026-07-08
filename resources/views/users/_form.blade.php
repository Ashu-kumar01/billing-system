@php
    $user = $user ?? null;
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <x-input-label for="name" value="Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 w-full" :value="old('name', $user->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 w-full" :value="old('email', $user->email ?? '')" required />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="phone" value="Phone" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 w-full" :value="old('phone', $user->phone ?? '')" />
        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="role" value="Role" />
        <select id="role" name="role" class="form-select mt-1 w-full" required>
            <option value="">Select role</option>
            @foreach (['owner', 'admin', 'manager', 'cashier'] as $role)
                <option value="{{ $role }}" @selected(old('role', $user->role ?? '') === $role)>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('role')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="password" :value="isset($user) ? 'New Password (leave blank to keep current)' : 'Password'" />
        <x-text-input id="password" name="password" type="password" class="mt-1 w-full" {{ isset($user) ? '' : 'required' }} />
        <x-input-error :messages="$errors->get('password')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="password_confirmation" value="Confirm Password" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 w-full" />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
    </div>

    <div class="flex items-center gap-2 sm:col-span-2">
        <input type="hidden" name="status" value="0">
        <input
            type="checkbox"
            id="status"
            name="status"
            value="1"
            class="h-4 w-4 rounded border-border text-primary-600 focus:ring-primary-500"
            @checked(old('status', $user->status ?? true))
        >
        <x-input-label for="status" value="Active" class="!mb-0" />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="btn-primary">
        <i class="fa-solid fa-check"></i> {{ isset($user) && $user->exists ? 'Update User' : 'Save User' }}
    </button>
    <a href="{{ route('users.index') }}" class="btn-secondary">Cancel</a>
</div>
