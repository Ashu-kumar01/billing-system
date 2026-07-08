<x-app-layout>
    <x-breadcrumb :items="['Settings' => null]" />

    <x-page-header title="Settings" subtitle="Configure company information and application preferences." />

    <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="card p-6">
            <h2 class="mb-4 text-lg font-semibold text-ink">Company Information</h2>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="company_name" value="Company Name" />
                    <x-text-input id="company_name" name="company_name" type="text" class="mt-1 w-full" :value="old('company_name', $settings['company_name'])" />
                    <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="company_email" value="Company Email" />
                    <x-text-input id="company_email" name="company_email" type="email" class="mt-1 w-full" :value="old('company_email', $settings['company_email'])" />
                    <x-input-error :messages="$errors->get('company_email')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="company_phone" value="Company Phone" />
                    <x-text-input id="company_phone" name="company_phone" type="text" class="mt-1 w-full" :value="old('company_phone', $settings['company_phone'])" />
                    <x-input-error :messages="$errors->get('company_phone')" class="mt-1" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="company_address" value="Company Address" />
                    <textarea id="company_address" name="company_address" rows="3" class="form-textarea mt-1 w-full">{{ old('company_address', $settings['company_address']) }}</textarea>
                    <x-input-error :messages="$errors->get('company_address')" class="mt-1" />
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h2 class="mb-4 text-lg font-semibold text-ink">Invoice Settings</h2>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="invoice_prefix" value="Invoice Prefix" />
                    <x-text-input id="invoice_prefix" name="invoice_prefix" type="text" class="mt-1 w-full" :value="old('invoice_prefix', $settings['invoice_prefix'])" />
                    <x-input-error :messages="$errors->get('invoice_prefix')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="gst_rate" value="Default GST Rate (%)" />
                    <x-text-input id="gst_rate" name="gst_rate" type="number" step="0.01" min="0" max="100" class="mt-1 w-full" :value="old('gst_rate', $settings['gst_rate'])" />
                    <x-input-error :messages="$errors->get('gst_rate')" class="mt-1" />
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h2 class="mb-4 text-lg font-semibold text-ink">Regional Settings</h2>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="currency_symbol" value="Currency Symbol" />
                    <x-text-input id="currency_symbol" name="currency_symbol" type="text" class="mt-1 w-full" :value="old('currency_symbol', $settings['currency_symbol'])" />
                    <x-input-error :messages="$errors->get('currency_symbol')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="timezone" value="Timezone" />
                    <select id="timezone" name="timezone" class="form-select mt-1 w-full">
                        @foreach ($timezones as $tz)
                            <option value="{{ $tz }}" @selected(old('timezone', $settings['timezone']) === $tz)>{{ $tz }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('timezone')" class="mt-1" />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-check"></i> Save Settings
            </button>
        </div>
    </form>
</x-app-layout>
