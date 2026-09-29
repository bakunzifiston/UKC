<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $slug = trim((string) $this->input('slug', ''));

        $this->merge([
            'slug' => $slug === '' ? Str::slug((string) $this->input('name'), '.') : Str::slug($slug, '.'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:permissions,slug'],
            'module' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
