<?php

use App\Models\Client;
use App\Models\GrowthLog;
use App\Models\Hydroponic;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Sale;
use App\Models\Site;
use App\Models\StaffMember;
use App\Models\Visitor;

Route::get('/sites', function () {
    return response()->json(Site::all());
});

Route::get('/hydroponics', function () {
    return response()->json(Hydroponic::all());
});

Route::get('/product_suppliers', function () {
    return response()->json(ProductSupplier::all());
});

Route::get('/clients', function () {
    return response()->json(Client::all());
});

Route::get('/sales', function () {
    return response()->json(Sale::all());
});

Route::get('/growth_logs', function () {
    return response()->json(GrowthLog::all());
});

Route::get('/products', function () {
    return response()->json(Product::all());
});

Route::get('/visitors', function () {
    return response()->json(Visitor::all());
});

Route::get('/staff_members', function () {
    return response()->json(StaffMember::all());
});
