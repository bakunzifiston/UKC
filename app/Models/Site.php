<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    // Define the table name if it differs from the default
    // protected $table = 'sites'; // Uncomment if your table name differs

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'site_name',      // Allow mass assignment for site_name
        'province',       // Allow mass assignment for location
        'district',        // Allow mass assignment for manager (nullable)
        'sector',
        'village',        // Allow mass assignment for manager (nullable)
        'googlemap',
        'manager_name',        // Allow mass assignment for manager (nullable)
        'contact-details', // Allow mass assignment for contact-details
    ];

    // Optionally, define the date format for created_at and updated_at columns
    protected $dates = ['created_at', 'updated_at'];
}
