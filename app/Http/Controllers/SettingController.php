<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected function defaults(): array
    {
        return [
            'company_name' => '',
            'company_email' => '',
            'company_phone' => '',
            'company_address' => '',
            'invoice_prefix' => 'INV-',
            'currency_symbol' => '₹',
            'gst_rate' => 18,
            'timezone' => 'Asia/Kolkata',
        ];
    }

    public function edit()
    {
        $settings = collect($this->defaults())
            ->map(fn ($default, $key) => Setting::get($key, $default));

        $timezones = [
            'Asia/Kolkata', 'UTC', 'America/New_York', 'America/Los_Angeles',
            'Europe/London', 'Europe/Berlin', 'Asia/Dubai', 'Asia/Singapore',
            'Australia/Sydney',
        ];

        return view('settings.edit', compact('settings', 'timezones'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:20'],
            'company_address' => ['nullable', 'string'],
            'invoice_prefix' => ['nullable', 'string', 'max:20'],
            'currency_symbol' => ['nullable', 'string', 'max:5'],
            'gst_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:64'],
        ]);

        foreach ($this->defaults() as $key => $default) {
            $type = in_array($key, ['gst_rate']) ? 'numeric' : 'string';
            Setting::set($key, $validated[$key] ?? '', $type);
        }

        return redirect()->route('settings.edit')->with('success', 'Settings updated successfully.');
    }
}
