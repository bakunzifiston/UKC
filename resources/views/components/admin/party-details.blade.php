@props(['record'])

<div class="max-w-3xl space-y-6 rounded-lg border border-gray-200 bg-white p-6 text-sm shadow-sm">
    <section class="space-y-2">
        <h3 class="border-b border-gray-200 pb-2 text-sm font-semibold text-gray-900">Name</h3>
        <div><span class="font-medium text-gray-500">First name:</span> {{ $record->first_name ?: '—' }}</div>
        <div><span class="font-medium text-gray-500">Second name:</span> {{ $record->second_name ?: '—' }}</div>
        @if (blank($record->first_name) && blank($record->second_name))
            <div><span class="font-medium text-gray-500">Name:</span> {{ $record->displayName() }}</div>
        @endif
    </section>

    <section class="space-y-2">
        <h3 class="border-b border-gray-200 pb-2 text-sm font-semibold text-gray-900">Location</h3>
        <div><span class="font-medium text-gray-500">Country:</span> {{ $record->country ?: '—' }}</div>
        @if ($record->isInRwanda())
            <div><span class="font-medium text-gray-500">Province:</span> {{ $record->province ?: '—' }}</div>
            <div><span class="font-medium text-gray-500">District:</span> {{ $record->district ?: '—' }}</div>
            <div><span class="font-medium text-gray-500">Sector:</span> {{ $record->sector ?: '—' }}</div>
            <div><span class="font-medium text-gray-500">Cell:</span> {{ $record->cell ?: '—' }}</div>
            <div><span class="font-medium text-gray-500">Village:</span> {{ $record->village ?: '—' }}</div>
        @elseif (filled($record->address))
            <div><span class="font-medium text-gray-500">Address:</span> {{ $record->address }}</div>
        @endif
    </section>

    <section class="space-y-2">
        <h3 class="border-b border-gray-200 pb-2 text-sm font-semibold text-gray-900">Personal / contact details</h3>
        <div><span class="font-medium text-gray-500">Age:</span> {{ filled($record->age) ? $record->age : '—' }}</div>
        <div><span class="font-medium text-gray-500">Gender:</span> {{ $record->gender ?: '—' }}</div>
        <div><span class="font-medium text-gray-500">Email:</span> {{ $record->email ?: '—' }}</div>
        <div><span class="font-medium text-gray-500">Telephone number:</span> {{ $record->displayTelephone() }}</div>
    </section>
</div>
