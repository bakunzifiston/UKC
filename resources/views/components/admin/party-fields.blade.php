@props(['record' => null])

@php
    $inputClass = 'mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm';
    $legacyName = trim((string) ($record?->client_name ?? $record?->supplier_name ?? ''));
    $legacyFirst = null;
    $legacySecond = null;
    if (blank($record?->first_name) && blank($record?->second_name) && $legacyName !== '') {
        $parts = preg_split('/\s+/', $legacyName) ?: [];
        $legacyFirst = array_shift($parts) ?: null;
        $legacySecond = $parts === [] ? null : implode(' ', $parts);
    }
    $countryValue = old('country', $record?->country ?? \App\Support\PartyProfile::LOCAL_COUNTRY);
    $isLocal = \App\Support\PartyProfile::isLocalCountry($countryValue);
    $value = function (string $field, mixed $fallback = null) use ($record) {
        return old($field, $record?->{$field} ?? $fallback);
    };
    $firstName = old('first_name', $record?->first_name ?: $legacyFirst);
    $secondName = old('second_name', $record?->second_name ?: $legacySecond);
    $telephone = old('telephone', $record?->telephone ?: $record?->contact_info);
@endphp

<div data-party-profile class="space-y-8">
    <section class="space-y-4">
        <h3 class="border-b border-gray-200 pb-2 text-sm font-semibold text-gray-900">Name</h3>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700" for="first_name">First name</label>
                <input id="first_name" type="text" name="first_name" value="{{ $firstName }}" required class="{{ $inputClass }}">
                <x-admin.field-error name="first_name" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="second_name">Second name</label>
                <input id="second_name" type="text" name="second_name" value="{{ $secondName }}" required class="{{ $inputClass }}">
                <x-admin.field-error name="second_name" />
            </div>
        </div>
    </section>

    <section class="space-y-4">
        <h3 class="border-b border-gray-200 pb-2 text-sm font-semibold text-gray-900">Location</h3>
        <div>
            <label class="block text-sm font-medium text-gray-700" for="country">Country</label>
            <input id="country" data-party-country type="text" name="country" value="{{ $countryValue }}" required list="party-countries" class="{{ $inputClass }}" autocomplete="country-name">
            <datalist id="party-countries">
                <option value="{{ \App\Support\PartyProfile::LOCAL_COUNTRY }}"></option>
            </datalist>
            <p class="mt-1 text-xs text-gray-500">Enter Rwanda to record province, district, sector, cell, and village. For any other country, only the country is kept.</p>
            <x-admin.field-error name="country" />
        </div>
        <div data-party-local @class(['hidden' => ! $isLocal])>
            <div class="grid gap-4 rounded-md bg-gray-50 p-4 sm:grid-cols-2">
            @foreach (['province' => 'Province', 'district' => 'District', 'sector' => 'Sector', 'cell' => 'Cell', 'village' => 'Village'] as $field => $label)
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="{{ $field }}">{{ $label }}</label>
                    <input id="{{ $field }}" type="text" name="{{ $field }}" value="{{ $value($field) }}" @if ($isLocal) required @endif @disabled(! $isLocal) class="{{ $inputClass }}">
                    <x-admin.field-error name="{{ $field }}" />
                </div>
            @endforeach
            </div>
        </div>
    </section>

    <section class="space-y-4">
        <h3 class="border-b border-gray-200 pb-2 text-sm font-semibold text-gray-900">Personal / contact details</h3>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700" for="age">Age <span class="font-normal text-gray-400">(optional)</span></label>
                <input id="age" type="number" name="age" value="{{ $value('age') }}" min="1" max="120" class="{{ $inputClass }}">
                <x-admin.field-error name="age" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="gender">Gender</label>
                <select id="gender" name="gender" required class="{{ $inputClass }}">
                    <option value="">Select gender</option>
                    @foreach (\App\Support\PartyProfile::GENDERS as $gender)
                        <option value="{{ $gender }}" @selected($value('gender') === $gender)>{{ $gender }}</option>
                    @endforeach
                </select>
                <x-admin.field-error name="gender" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="email">Email <span class="font-normal text-gray-400">(optional)</span></label>
                <input id="email" type="email" name="email" value="{{ $value('email') }}" class="{{ $inputClass }}">
                <x-admin.field-error name="email" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="telephone">Telephone number</label>
                <input id="telephone" type="tel" name="telephone" value="{{ $telephone }}" required maxlength="30" class="{{ $inputClass }}">
                <x-admin.field-error name="telephone" />
            </div>
        </div>
    </section>
</div>
<script>
    (() => {
        const root = document.currentScript.previousElementSibling;
        if (!root) {
            return;
        }

        const country = root.querySelector('[data-party-country]');
        const local = root.querySelector('[data-party-local]');
        if (!country || !local) {
            return;
        }

        const fields = local.querySelectorAll('input');

        const sync = () => {
            const show = country.value.trim().toLowerCase() === 'rwanda';
            local.classList.toggle('hidden', !show);
            fields.forEach((field) => {
                field.disabled = !show;
                field.required = show;
            });
        };

        country.addEventListener('input', sync);
        sync();
    })();
</script>
