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
        Schema::create('sales', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete(); // Foreign Key*/
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete(); // Foreign Key
            $table->integer('quantity_sold');
            $table->decimal('total_price', 10, 2);
            $table->date('sale_date');
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
