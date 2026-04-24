<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'min:4', 'max:255'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'price_to' => ['nullable', 'numeric', 'min:0', Rule::when($this->filled('price_from'), 'gte:price_from')],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'in_stock' => ['nullable', 'in:0,1,true,false'],
            'rating_from' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'sort' => ['nullable', 'string', 'in:price_asc,price_desc,rating_desc,newest'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
