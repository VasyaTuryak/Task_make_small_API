<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddItemsToOrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:1',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'items.*.product_name.required' => 'Product name is required',
            'items.*.product_name.string' => 'Product name has to be string',

            'items.*.quantity.required' => 'Product quantity is required',
            'items.*.quantity.integer' => 'Product quantity has to be integer',
            'items.*.quantity.min' => 'Product quantity has to be greater then 1',

            'items.*.price.required' => 'Price is required',
            'items.*.numeric.required' => 'Price has to be numeric',
            'items.*.price.min' => 'Product price has to be greater then 1',
        ];
    }
}
