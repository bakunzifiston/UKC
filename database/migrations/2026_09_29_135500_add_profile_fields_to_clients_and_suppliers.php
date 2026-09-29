<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['clients', 'suppliers'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('first_name')->nullable();
                $table->string('second_name')->nullable();
                $table->string('country')->nullable();
                $table->string('province')->nullable();
                $table->string('district')->nullable();
                $table->string('sector')->nullable();
                $table->string('cell')->nullable();
                $table->string('village')->nullable();
                $table->unsignedTinyInteger('age')->nullable();
                $table->string('gender')->nullable();
                $table->string('email')->nullable();
                $table->string('telephone')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['clients', 'suppliers'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn([
                    'first_name',
                    'second_name',
                    'country',
                    'province',
                    'district',
                    'sector',
                    'cell',
                    'village',
                    'age',
                    'gender',
                    'email',
                    'telephone',
                ]);
            });
        }
    }
};
