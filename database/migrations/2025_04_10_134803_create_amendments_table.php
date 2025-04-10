<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amendments', function (Blueprint $table) {
            $table->id('amendment_id');
            $table->string('amendment_name');
            $table->string('amendment_content');
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amendments');
    }
};
