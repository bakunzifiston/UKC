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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->foreignId('hydroponics_id')->constrained('hydroponics')->cascadeOnDelete(); // Foreign Key
            $table->string('product_name');
            $table->string('product_type');
            $table->integer('quantity')->default(0);
            $table->decimal('unit_price', 10, 2);
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
