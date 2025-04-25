<?php

use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->string('num')->primary();
            $table->date('birthday')->nullable(false);
            $table->date('acquirement_date')->nullable(false);
            $table->date('distribution_date')->nullable(false);
            $table->string('country')->nullable(false);
            $table->foreignIdFor(Customer::class)->unique()->nullable(false)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
