<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_id' => ['nullable', 'required_without:purchase_id', 'exists:invoices,id'],
            'purchase_id' => ['nullable', 'required_without:invoice_id', 'exists:purchases,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:cash,card,upi,bank_transfer,cheque,other'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'payment_date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'invoice_id.required_without' => 'Please select either an invoice or a purchase.',
            'purchase_id.required_without' => 'Please select either an invoice or a purchase.',
        ];
    }
}
