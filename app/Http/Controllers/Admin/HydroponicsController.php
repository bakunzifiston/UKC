<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHydroponicsRequest;
use App\Http\Requests\Admin\UpdateHydroponicsRequest;
use App\Models\Hydroponics;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HydroponicsController extends Controller
{
    public function index(): View
    {
        return view('admin.hydroponics.index');
    }

    public function create(): View
    {
        return view('admin.hydroponics.create', [
            'sites' => Site::orderBy('site_name')->get(),
        ]);
    }

    public function store(StoreHydroponicsRequest $request): RedirectResponse
    {
        Hydroponics::create($request->validated());

        return redirect()->route('admin.hydroponics.index')->with('success', 'Category created.');
    }

    public function show(Hydroponics $hydroponic): View
    {
        $hydroponic->load('site');

        return view('admin.hydroponics.show', ['hydroponics' => $hydroponic]);
    }

    public function edit(Hydroponics $hydroponic): View
    {
        return view('admin.hydroponics.edit', [
            'hydroponics' => $hydroponic,
            'sites' => Site::orderBy('site_name')->get(),
        ]);
    }

    public function update(UpdateHydroponicsRequest $request, Hydroponics $hydroponic): RedirectResponse
    {
        $hydroponic->update($request->validated());

        return redirect()->route('admin.hydroponics.index')->with('success', 'Category updated.');
    }

    public function destroy(Hydroponics $hydroponic): RedirectResponse
    {
        $hydroponic->delete();

        return redirect()->route('admin.hydroponics.index')->with('success', 'Category deleted.');
    }
}
