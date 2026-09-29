<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Support\PartyProfile;

trait ValidatesPartyProfile
{
    protected function prepareForValidation(): void
    {
        $this->merge(PartyProfile::normalize($this->all()));
    }

    public function rules(): array
    {
        return PartyProfile::rules();
    }

    public function attributes(): array
    {
        return PartyProfile::attributes();
    }
}
