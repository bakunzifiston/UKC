<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'sector' => ['required', 'string', 'max:255'],
            'village' => ['required', 'string', 'max:255'],
            'googlemap' => ['required', 'string', 'max:255'],
            'manager_name' => ['required', 'string', 'max:255'],
            'contact-details' => ['required', 'string', 'max:255'],
        ];
    }
}
