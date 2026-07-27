<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVisitorRequest;
use App\Http\Requests\Admin\UpdateVisitorRequest;
use App\Models\Visitor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VisitorController extends Controller
{
    public function index(): View
    {
        return view('admin.visitors.index');
    }

    public function create(): View
    {
        return view('admin.visitors.create');
    }

    public function store(StoreVisitorRequest $request): RedirectResponse
    {
        Visitor::create($request->validated());

        return redirect()->route('admin.visitors.index')->with('success', 'Record created.');
    }

    public function edit(Visitor $visitor): View
    {
        return view('admin.visitors.edit', compact('visitor'));
    }

    public function update(UpdateVisitorRequest $request, Visitor $visitor): RedirectResponse
    {
        $visitor->update($request->validated());

        return redirect()->route('admin.visitors.index')->with('success', 'Record updated.');
    }

    public function destroy(Visitor $visitor): RedirectResponse
    {
        $visitor->delete();

        return redirect()->route('admin.visitors.index')->with('success', 'Record deleted.');
    }
}
