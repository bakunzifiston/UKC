<?php

namespace App\Support;

use Illuminate\Validation\Rule;

class PartyProfile
{
    public const LOCAL_COUNTRY = 'Rwanda';

    public const GENDERS = ['Male', 'Female'];

    public const LOCAL_FIELDS = ['province', 'district', 'sector', 'cell', 'village'];

    public static function isLocalCountry(?string $country): bool
    {
        return strcasecmp(trim((string) $country), self::LOCAL_COUNTRY) === 0;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public static function normalize(array $input): array
    {
        $country = trim((string) ($input['country'] ?? ''));
        $isLocal = self::isLocalCountry($country);

        if ($isLocal) {
            $country = self::LOCAL_COUNTRY;
        }

        $normalized = [
            'first_name' => self::blankToNull($input['first_name'] ?? null),
            'second_name' => self::blankToNull($input['second_name'] ?? null),
            'country' => $country === '' ? null : $country,
            'age' => self::blankToNull($input['age'] ?? null),
            'gender' => self::blankToNull($input['gender'] ?? null),
            'email' => self::blankToNull($input['email'] ?? null),
            'telephone' => self::blankToNull($input['telephone'] ?? null),
        ];

        foreach (self::LOCAL_FIELDS as $field) {
            $normalized[$field] = $isLocal ? self::blankToNull($input[$field] ?? null) : null;
        }

        return $normalized;
    }

    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        $localRules = ['nullable', 'string', 'max:255'];
        $localRequired = 'required_if:country,'.self::LOCAL_COUNTRY;

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'second_name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'gender' => ['required', Rule::in(self::GENDERS)],
            'email' => ['nullable', 'email', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
        ];

        foreach (self::LOCAL_FIELDS as $field) {
            $rules[$field] = array_merge([$localRequired], $localRules);
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public static function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'second_name' => 'second name',
            'telephone' => 'telephone number',
        ];
    }

    private static function blankToNull(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (! is_string($value) && ! is_numeric($value)) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
