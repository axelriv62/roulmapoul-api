<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id('rental_id');
            $table->dateTime('rental_start');
            $table->dateTime('rental_end');
            $table->json('rental_options')->nullable();
            $table->integer('rental_nb_days');
            $table->float('rental_total_price');
            $table->foreignId('cust_id')->constrained('customers')->onDelete('cascade');
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
