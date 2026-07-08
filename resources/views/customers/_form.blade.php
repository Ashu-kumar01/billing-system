@php
    $customer = $customer ?? null;
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <x-input-label for="name" value="Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 w-full" :value="old('name', $customer->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 w-full" :value="old('email', $customer->email ?? '')" />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="phone" value="Phone" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 w-full" :value="old('phone', $customer->phone ?? '')" />
        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="opening_balance" value="Opening Balance" />
        <x-text-input id="opening_balance" name="opening_balance" type="number" step="0.01" min="0" class="mt-1 w-full" :value="old('opening_balance', $customer->opening_balance ?? 0)" />
        <x-input-error :messages="$errors->get('opening_balance')" class="mt-1" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="address" value="Address" />
        <x-text-input id="address" name="address" type="text" class="mt-1 w-full" :value="old('address', $customer->address ?? '')" />
        <x-input-error :messages="$errors->get('address')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="city" value="City" />
        <x-text-input id="city" name="city" type="text" class="mt-1 w-full" :value="old('city', $customer->city ?? '')" />
        <x-input-error :messages="$errors->get('city')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="state" value="State" />
        <x-text-input id="state" name="state" type="text" class="mt-1 w-full" :value="old('state', $customer->state ?? '')" />
        <x-input-error :messages="$errors->get('state')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="country" value="Country" />
        <x-text-input id="country" name="country" type="text" class="mt-1 w-full" :value="old('country', $customer->country ?? '')" />
        <x-input-error :messages="$errors->get('country')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="zipcode" value="Zipcode" />
        <x-text-input id="zipcode" name="zipcode" type="text" class="mt-1 w-full" :value="old('zipcode', $customer->zipcode ?? '')" />
        <x-input-error :messages="$errors->get('zipcode')" class="mt-1" />
    </div>

    <div class="flex items-center gap-2 sm:col-span-2">
        <input type="hidden" name="status" value="0">
        <input
            type="checkbox"
            id="status"
            name="status"
            value="1"
            class="h-4 w-4 rounded border-border text-primary-600 focus:ring-primary-500"
            @checked(old('status', $customer->status ?? true))
        >
        <x-input-label for="status" value="Active" class="!mb-0" />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="btn-primary">
        <i class="fa-solid fa-check"></i> {{ isset($customer) && $customer->exists ? 'Update Customer' : 'Save Customer' }}
    </button>
    <a href="{{ route('customers.index') }}" class="btn-secondary">Cancel</a>
</div>
