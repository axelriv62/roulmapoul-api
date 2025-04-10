<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->string('car_registration')->primary();
            $table->enum('car_type', ['type1', 'type2', 'type3']);
            $table->string('car_condition');
            $table->float('car_remaining_gas');
            $table->float('car_price_day');
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
