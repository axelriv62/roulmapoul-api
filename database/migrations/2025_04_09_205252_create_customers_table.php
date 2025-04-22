<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable(false)->unique();
            $table->string('phone');
            $table->string('num');
            $table->string('street');
            $table->string('zip');
            $table->string('city');
            $table->string('country');
            $table->string('num_bill')->nullable();
            $table->string('street_bill')->nullable();
            $table->string('zip_bill')->nullable();
            $table->string('city_bill')->nullable();
            $table->string('country_bill')->nullable();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
