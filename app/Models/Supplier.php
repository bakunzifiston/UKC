<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // Define the table name if it differs from the default
    // protected $table = 'suppliers'; // Uncomment if your table name differs

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'supplier_name',   // Allow mass assignment for supplier_name
        'contact_info',    // Allow mass assignment for contact_info
        'address',         // Allow mass assignment for address (nullable)
    ];

    // Optionally, define the date format for created_at and updated_at columns
    protected $dates = ['created_at', 'updated_at'];
}
