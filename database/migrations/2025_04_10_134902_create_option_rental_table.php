<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('option_rental', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_id')->constrained('options')->onDelete('cascade');
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('option_rental');
    }
};
