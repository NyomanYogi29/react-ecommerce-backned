<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'name' => ['string', 'required'],
            'category' => ['string', 'required'],
            'stock' => ['integer'],
            'description' => ['string'],
            'price' => ['numeric', 'decimal:0,2', 'min:0', 'required'],
            'image' => ['string', 'nullable'],
        ];
    }
}
