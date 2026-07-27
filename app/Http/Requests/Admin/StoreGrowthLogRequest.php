<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreGrowthLogRequest extends FormRequest
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
            'product_id' => ['required', 'exists:products,id'],
            'day_number' => ['required', 'integer', 'min:1', 'max:16'],
            'growth_value' => ['required', 'numeric'],
            'log_date' => ['required', 'date'],
        ];
    }
}
