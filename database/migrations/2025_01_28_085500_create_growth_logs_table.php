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
        Schema::create('growth_logs', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete(); // Foreign Key
            $table->foreignId('hydroponics_id')->constrained('hydroponics')->cascadeOnDelete(); // Foreign Key
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete(); // Foreign Key*/
            $table->integer('day_number'); // 1 to 7
            $table->decimal('growth_value', 8, 2); // e.g., height in cm
            $table->date('log_date');
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_logs');
    }
};
