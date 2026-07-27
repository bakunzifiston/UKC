<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_suppliers', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete(); // Foreign Key*/
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete(); // Foreign Key
            $table->integer('supplied_quantity');
            $table->date('supplied_date');
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_suppliers');
    }
};
