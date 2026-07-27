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
        Schema::create('hydroponics', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete(); // Foreign Key
            $table->string('hydroponics_name');
            $table->text('description')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hydroponics');
    }
};
