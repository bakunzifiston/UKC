<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_id' => ['required', 'exists:sites,id'],
            'hydroponics_id' => ['required', 'exists:hydroponics,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'product_type' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
