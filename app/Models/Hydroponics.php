<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hydroponics extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'site_name',        // Allow mass assignment for site_id (foreign key)
        'hydroponics_name', // Allow mass assignment for hydroponics_name
        'description',

    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
