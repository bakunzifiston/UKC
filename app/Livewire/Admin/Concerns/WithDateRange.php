<?php

namespace App\Livewire\Admin\Concerns;

use Carbon\Carbon;

trait WithDateRange
{
    public string $dateFrom = '';

    public string $dateTo = '';

    public string $preset = 'all';

    public function mountWithDateRange(string $defaultPreset = 'all'): void
    {
        $this->preset = $defaultPreset;
        $this->applyPreset($defaultPreset);
    }

    public function updatedPreset(string $value): void
    {
        if ($value !== 'custom') {
            $this->applyPreset($value);
        }

        $this->resetDateFilterPage();
    }

    public function updatedDateFrom(): void
    {
        $this->preset = 'custom';
        $this->resetDateFilterPage();
    }

    public function updatedDateTo(): void
    {
        $this->preset = 'custom';
        $this->resetDateFilterPage();
    }

    protected function resetDateFilterPage(): void
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function applyPreset(string $preset): void
    {
        $now = Carbon::now();

        if ($preset === 'week') {
            $this->dateFrom = $now->copy()->startOfWeek()->toDateString();
            $this->dateTo = $now->copy()->endOfWeek()->toDateString();

            return;
        }

        if ($preset === 'quarter') {
            $this->dateFrom = $now->copy()->firstOfQuarter()->toDateString();
            $this->dateTo = $now->copy()->lastOfQuarter()->toDateString();

            return;
        }

        if ($preset === 'year') {
            $this->dateFrom = $now->copy()->startOfYear()->toDateString();
            $this->dateTo = $now->copy()->endOfYear()->toDateString();

            return;
        }

        if ($preset === 'all') {
            $this->dateFrom = '';
            $this->dateTo = '';

            return;
        }

        $this->dateFrom = $now->copy()->startOfMonth()->toDateString();
        $this->dateTo = $now->copy()->endOfMonth()->toDateString();
    }

    protected function previousPeriodBounds(): array
    {
        if ($this->dateFrom === '' || $this->dateTo === '') {
            return [null, null];
        }

        $from = Carbon::parse($this->dateFrom)->startOfDay();
        $to = Carbon::parse($this->dateTo)->endOfDay();
        $days = $from->diffInDays($to) + 1;

        return [
            $from->copy()->subDays($days)->toDateString(),
            $from->copy()->subDay()->toDateString(),
        ];
    }

    protected function formatTrend(?float $current, ?float $previous, bool $comparable = true): array
    {
        if (! $comparable) {
            return ['trend' => null, 'positive' => true];
        }

        $current = (float) $current;
        $previous = (float) $previous;

        if ($previous == 0.0) {
            if ($current == 0.0) {
                return ['trend' => 'No change vs prior period', 'positive' => true];
            }

            return ['trend' => 'New vs prior period', 'positive' => true];
        }

        $pct = (($current - $previous) / abs($previous)) * 100;
        $sign = $pct >= 0 ? '+' : '';

        return [
            'trend' => sprintf('%s%s%% vs prior period', $sign, number_format($pct, 1)),
            'positive' => $pct >= 0,
        ];
    }

    protected function applyDateFilter($query, string $column = 'created_at')
    {
        return $query
            ->when($this->dateFrom !== '', fn ($q) => $q->whereDate($column, '>=', $this->dateFrom))
            ->when($this->dateTo !== '', fn ($q) => $q->whereDate($column, '<=', $this->dateTo));
    }
}
