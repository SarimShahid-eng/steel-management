<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleUpdateRequest extends FormRequest
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
            'update_id' => ['required', 'integer', 'exists:sales,id'],
            'customer_account_id' => ['required', 'integer', 'exists:accounts,id'],
            'payment_type' => ['required','in:cash,bank,credit'],
            'payment_account_id' => ['nullable', 'required_if:payment_type,cash', 'required_if:payment_type,bank', 'exists:accounts,id'],
            'notes' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'received_amount' => ['required_if:payment_type,cash', 'required_if:payment_type,bank' , 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:1'],
            'remaining_amount' => ['required', 'numeric'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.weight' => ['required', 'numeric', 'min:1'],
            'items.*.rate' => ['required', 'numeric', 'min:1'],
            'items.*.amount' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'payment_account_id.required_if' => 'Payment Account is required',
            'customer_account_id.required' => 'The customer account  field is required.',
            'payment_type.required' => 'The payment account  field is required.',
            // 'items.*.qty.required' => 'The Item quantity field is required.',
            'items.*.weight.required' => 'The Item weight field is required.',
            'items.*.product_id.required' => 'The Item product field is required.',
            'items.*.product_id.distinct' => 'Each Item must be different.',
            'items.*.rate.required' => 'The Item rate field is required.',
            'items.*.amount.required' => 'The Item amount must be present.',
            'items.*.amount.min' => 'The Item amount must be present.',
        ];

    }
}
