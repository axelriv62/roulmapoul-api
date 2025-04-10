<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->string('license_num')->primary();
            $table->date('license_date_acquisition');
            $table->string('license_date_distribution');
            $table->string('license_country');
            $table->foreignId('cust_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
