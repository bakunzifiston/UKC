<x-admin.filter-field label="Period" wire-model="preset" type="select">
    <option value="all">All time</option>
    <option value="year">This year</option>
    <option value="quarter">This quarter</option>
    <option value="month">This month</option>
    <option value="week">This week</option>
    <option value="custom">Custom</option>
</x-admin.filter-field>
<x-admin.filter-field label="From" wire-model="dateFrom" type="date" />
<x-admin.filter-field label="To" wire-model="dateTo" type="date" />
