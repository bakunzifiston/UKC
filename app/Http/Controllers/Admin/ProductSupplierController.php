<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductSupplierRequest;
use App\Http\Requests\Admin\UpdateProductSupplierRequest;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductSupplierController extends Controller
{
    public function index(): View
    {
        return view('admin.product-suppliers.index');
    }

    public function create(): View
    {
        return view('admin.product-suppliers.create', [
            'products' => Product::orderBy('product_name')->get(),
            'suppliers' => Supplier::orderBy('supplier_name')->get(),
        ]);
    }

    public function store(StoreProductSupplierRequest $request): RedirectResponse
    {
        ProductSupplier::create($request->validated());

        return redirect()->route('admin.product-suppliers.index')->with('success', 'Product supplier created.');
    }

    public function show(ProductSupplier $productSupplier): View
    {
        $productSupplier->load(['product', 'supplier']);

        return view('admin.product-suppliers.show', compact('productSupplier'));
    }

    public function edit(ProductSupplier $productSupplier): View
    {
        return view('admin.product-suppliers.edit', [
            'productSupplier' => $productSupplier,
            'products' => Product::orderBy('product_name')->get(),
            'suppliers' => Supplier::orderBy('supplier_name')->get(),
        ]);
    }

    public function update(UpdateProductSupplierRequest $request, ProductSupplier $productSupplier): RedirectResponse
    {
        $productSupplier->update($request->validated());

        return redirect()->route('admin.product-suppliers.index')->with('success', 'Product supplier updated.');
    }

    public function destroy(ProductSupplier $productSupplier): RedirectResponse
    {
        $productSupplier->delete();

        return redirect()->route('admin.product-suppliers.index')->with('success', 'Product supplier deleted.');
    }
}
