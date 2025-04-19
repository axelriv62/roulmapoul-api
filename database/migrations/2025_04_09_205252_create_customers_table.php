<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('email')->nullable(false)->unique();
            $table->string('phone')->nullable(false);
            $table->string('num')->nullable(false);
            $table->string('street')->nullable(false);
            $table->string('zip')->nullable(false);
            $table->string('city')->nullable(false);
            $table->string('country')->nullable(false);
            $table->string('num_bill')->nullable(false);
            $table->string('street_bill')->nullable(false);
            $table->string('zip_bill')->nullable(false);
            $table->string('city_bill')->nullable(false);
            $table->string('country_bill')->nullable(false);
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
