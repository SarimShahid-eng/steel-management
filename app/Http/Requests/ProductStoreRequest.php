<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
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
            'update_id' => ['nullable', 'exists:products,id'],
            'name' => ['required', 'string', Rule::unique('products', 'name')->ignore($this->update_id)],
            'description' => ['nullable', 'string'],
        ];
    }
}
