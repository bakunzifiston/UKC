<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthLog extends Model
{
    use HasFactory;

    protected $fillable = [

        'site_id',
        'hydroponics_id',
        'product_id',
        'day_number',        // Allow mass assignment for client_id (foreign key)
        'growth_value', // Allow mass assignment for quantity_sold
        'log_date',

    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function hydroponics()
    {
        return $this->belongsTo(Hydroponics::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
