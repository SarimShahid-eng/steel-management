<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseUpdateRequest extends FormRequest
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
            'update_id' => ['required', 'integer', 'exists:purchases,id'],
            'supplier_account_id' => ['required', 'integer', 'exists:accounts,id'],
            'payment_type' => ['required', 'in:cash,bank,credit'],
            'payment_account_id' => ['nullable', 'required_if:payment_type,cash', 'required_if:payment_type,bank', 'exists:accounts,id'],
            'notes' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:1'],
            'remaining_amount' => ['required', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer',
            // 'distinct',
            'exists:products,id'],
            'items.*.weight' => ['required', 'numeric', 'min:1'],
            'items.*.rate' => ['required', 'numeric', 'min:1'],
            'items.*.amount' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_account_id.required' => 'The supplier account  field is required.',
            'payment_type.required' => 'The payment account  field is required.',
            'items.*.weight.required' => 'The Item weight field is required.',
            'items.*.product_id.required' => 'The Item product field is required.',
            'items.*.product_id.distinct' => 'Each Item must be different.',
            'items.*.rate.required' => 'The Item rate field is required.',
            'items.*.amount.required' => 'The Item amount must be present.',
            'items.*.amount.min' => 'The Item amount must be present.',

        ];

    }
}
