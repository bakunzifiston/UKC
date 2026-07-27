<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGrowthLogRequest;
use App\Http\Requests\Admin\UpdateGrowthLogRequest;
use App\Models\GrowthLog;
use App\Models\Hydroponics;
use App\Models\Product;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GrowthLogController extends Controller
{
    public function index(): View
    {
        return view('admin.growth-logs.index');
    }

    public function create(): View
    {
        $defaultDay = (Carbon::now()->diffInDays(Carbon::create(2024, 1, 1)) % 16) + 1;

        return view('admin.growth-logs.create', [
            'sites' => Site::orderBy('site_name')->get(),
            'hydroponics' => Hydroponics::orderBy('hydroponics_name')->get(),
            'products' => Product::orderBy('product_name')->get(),
            'defaultDay' => $defaultDay,
        ]);
    }

    public function store(StoreGrowthLogRequest $request): RedirectResponse
    {
        GrowthLog::create($request->validated());

        return redirect()->route('admin.growth-logs.index')->with('success', 'Growth log created.');
    }

    public function show(GrowthLog $growthLog): View
    {
        $growthLog->load(['site', 'hydroponics', 'product']);

        return view('admin.growth-logs.show', compact('growthLog'));
    }

    public function edit(GrowthLog $growthLog): View
    {
        return view('admin.growth-logs.edit', [
            'growthLog' => $growthLog,
            'sites' => Site::orderBy('site_name')->get(),
            'hydroponics' => Hydroponics::orderBy('hydroponics_name')->get(),
            'products' => Product::orderBy('product_name')->get(),
        ]);
    }

    public function update(UpdateGrowthLogRequest $request, GrowthLog $growthLog): RedirectResponse
    {
        $growthLog->update($request->validated());

        return redirect()->route('admin.growth-logs.index')->with('success', 'Growth log updated.');
    }

    public function destroy(GrowthLog $growthLog): RedirectResponse
    {
        $growthLog->delete();

        return redirect()->route('admin.growth-logs.index')->with('success', 'Growth log deleted.');
    }
}
