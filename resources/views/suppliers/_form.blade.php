@php
    $supplier = $supplier ?? null;
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <x-input-label for="name" value="Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 w-full" :value="old('name', $supplier->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="company_name" value="Company Name" />
        <x-text-input id="company_name" name="company_name" type="text" class="mt-1 w-full" :value="old('company_name', $supplier->company_name ?? '')" />
        <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 w-full" :value="old('email', $supplier->email ?? '')" />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="phone" value="Phone" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 w-full" :value="old('phone', $supplier->phone ?? '')" />
        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="gst_number" value="GST Number" />
        <x-text-input id="gst_number" name="gst_number" type="text" maxlength="15" class="mt-1 w-full" :value="old('gst_number', $supplier->gst_number ?? '')" />
        <x-input-error :messages="$errors->get('gst_number')" class="mt-1" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="address" value="Address" />
        <x-text-input id="address" name="address" type="text" class="mt-1 w-full" :value="old('address', $supplier->address ?? '')" />
        <x-input-error :messages="$errors->get('address')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="city" value="City" />
        <x-text-input id="city" name="city" type="text" class="mt-1 w-full" :value="old('city', $supplier->city ?? '')" />
        <x-input-error :messages="$errors->get('city')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="state" value="State" />
        <x-text-input id="state" name="state" type="text" class="mt-1 w-full" :value="old('state', $supplier->state ?? '')" />
        <x-input-error :messages="$errors->get('state')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="country" value="Country" />
        <x-text-input id="country" name="country" type="text" class="mt-1 w-full" :value="old('country', $supplier->country ?? '')" />
        <x-input-error :messages="$errors->get('country')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="zipcode" value="Zipcode" />
        <x-text-input id="zipcode" name="zipcode" type="text" class="mt-1 w-full" :value="old('zipcode', $supplier->zipcode ?? '')" />
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
            @checked(old('status', $supplier->status ?? true))
        >
        <x-input-label for="status" value="Active" class="!mb-0" />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="btn-primary">
        <i class="fa-solid fa-check"></i> {{ isset($supplier) && $supplier->exists ? 'Update Supplier' : 'Save Supplier' }}
    </button>
    <a href="{{ route('suppliers.index') }}" class="btn-secondary">Cancel</a>
</div>
