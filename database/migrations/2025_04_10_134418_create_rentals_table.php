<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id('id');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->json('options')->nullable();
            $table->integer('nb_days');
            $table->float('total_price');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('car_registration');
            $table->foreign('car_registration')->references('car_registration')->on('cars')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
