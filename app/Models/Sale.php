<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [

        'product_id',
        'client_id',        // Allow mass assignment for client_id (foreign key)
        'quantity_sold', // Allow mass assignment for quantity_sold
        'total_price',
        'sale_date', // Allow mass assignment for sale_date

    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
