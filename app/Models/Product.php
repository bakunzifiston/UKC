<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_id',
        'hydroponics_id',
        'hydroponics_name',
        'product_name',
        'product_type',
        'quantity',
        'unit_price',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function hydroponics(): BelongsTo
    {
        return $this->belongsTo(Hydroponics::class);
    }
}
