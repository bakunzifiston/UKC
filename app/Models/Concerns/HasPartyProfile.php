<?php

namespace App\Models\Concerns;

use App\Support\PartyProfile;
use Illuminate\Database\Eloquent\Model;

trait HasPartyProfile
{
    public static function bootHasPartyProfile(): void
    {
        static::saving(function (Model $model): void {
            $fullName = trim(implode(' ', array_filter(
                [$model->first_name, $model->second_name],
                fn ($part) => filled($part)
            )));

            if ($fullName !== '') {
                $model->{$model->partyDisplayNameColumn()} = $fullName;
            }

            if (filled($model->telephone)) {
                $model->contact_info = $model->telephone;
            }

            if (! PartyProfile::isLocalCountry($model->country)) {
                foreach (PartyProfile::LOCAL_FIELDS as $field) {
                    $model->{$field} = null;
                }
            }
        });
    }

    abstract protected function partyDisplayNameColumn(): string;

    public function displayName(): string
    {
        $fullName = trim(implode(' ', array_filter(
            [$this->first_name, $this->second_name],
            fn ($part) => filled($part)
        )));

        if ($fullName !== '') {
            return $fullName;
        }

        $legacy = $this->{$this->partyDisplayNameColumn()};

        return filled($legacy) ? (string) $legacy : '—';
    }

    public function displayTelephone(): string
    {
        if (filled($this->telephone)) {
            return (string) $this->telephone;
        }

        return filled($this->contact_info) ? (string) $this->contact_info : '—';
    }

    public function isInRwanda(): bool
    {
        return PartyProfile::isLocalCountry($this->country);
    }

    public function locationLabel(): string
    {
        if ($this->isInRwanda()) {
            $parts = array_filter(
                [$this->village, $this->cell, $this->sector, $this->district, $this->province, $this->country],
                fn ($part) => filled($part)
            );

            return $parts === [] ? '—' : implode(', ', $parts);
        }

        if (filled($this->country)) {
            return (string) $this->country;
        }

        return filled($this->address) ? (string) $this->address : '—';
    }
}
