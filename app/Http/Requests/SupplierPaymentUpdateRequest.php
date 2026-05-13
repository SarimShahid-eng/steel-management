<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierPaymentUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'update_id'=>['required','exists:payments,id'],
            'payment_account_id' => ['required', 'integer', 'exists:accounts,id'],
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'payment_type' => ['required', 'in:cash,bank'],
            // 'payment_account_id' => ['nullable', 'required_if:payment_type,cash', 'required_if:payment_type,bank', 'exists:accounts,id'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.required' => 'The supplier account  field is required.',
            'payment_type.required' => 'The payment account  field is required.',
        ];

    }
}
