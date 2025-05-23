<?php

use App\Enums\RentalState;
use App\Models\Customer;
use App\Models\Warranty;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->date('start');
            $table->date('end');
            $table->integer('nb_days');
            $table->enum('state', RentalState::toValuesArray())->default(RentalState::PAID);
            $table->float('total_price')->default(0);
            $table->string('car_plate');
            $table->foreign('car_plate')->references('plate')->on('cars')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Warranty::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
