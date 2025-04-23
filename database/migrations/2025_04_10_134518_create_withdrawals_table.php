<?php

use App\Models\Customer;
use App\Models\Rental;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->float('fuel_level');
            $table->enum('interior_condition', \App\Enums\CarCondition::toValuesArray());
            $table->enum('exterior_condition', \App\Enums\CarCondition::toValuesArray());
            $table->float('mileage');
            $table->datetime('datetime');
            $table->string('comment')->nullable();
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Rental::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
