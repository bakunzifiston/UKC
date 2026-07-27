<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSaleRequest;
use App\Http\Requests\Admin\UpdateSaleRequest;
use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(): View
    {
        return view('admin.sales.index');
    }

    public function create(): View
    {
        return view('admin.sales.create', [
            'products' => Product::orderBy('product_name')->get(),
            'clients' => Client::orderBy('client_name')->get(),
        ]);
    }

    public function store(StoreSaleRequest $request): RedirectResponse
    {
        Sale::create($request->validated());

        return redirect()->route('admin.sales.index')->with('success', 'Sale created.');
    }

    public function show(Sale $sale): View
    {
        $sale->load(['product', 'client']);

        return view('admin.sales.show', compact('sale'));
    }

    public function edit(Sale $sale): View
    {
        return view('admin.sales.edit', [
            'sale' => $sale,
            'products' => Product::orderBy('product_name')->get(),
            'clients' => Client::orderBy('client_name')->get(),
        ]);
    }

    public function update(UpdateSaleRequest $request, Sale $sale): RedirectResponse
    {
        $sale->update($request->validated());

        return redirect()->route('admin.sales.index')->with('success', 'Sale updated.');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->delete();

        return redirect()->route('admin.sales.index')->with('success', 'Sale deleted.');
    }
}
