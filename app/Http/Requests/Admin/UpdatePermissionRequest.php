<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $permission = $this->route('permission');

        if ($permission?->is_system) {
            $this->merge([
                'slug' => $permission->slug,
                'module' => $permission->module,
            ]);

            return;
        }

        $slug = trim((string) $this->input('slug', ''));

        $this->merge([
            'slug' => $slug === '' ? Str::slug((string) $this->input('name'), '.') : Str::slug($slug, '.'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('permissions', 'slug')->ignore($this->route('permission'))],
            'module' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
