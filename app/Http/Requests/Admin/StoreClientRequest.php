<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesPartyProfile;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    use ValidatesPartyProfile;

    public function authorize(): bool
    {
        return true;
    }
}
