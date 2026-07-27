<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'client_id' => ['required', 'exists:clients,id'],
            'quantity_sold' => ['required', 'integer', 'min:0'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'sale_date' => ['required', 'date'],
        ];
    }
}
