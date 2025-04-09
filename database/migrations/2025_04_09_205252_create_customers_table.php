<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id('cust_id');
            $table->string('cust_first_name');
            $table->string('cust_last_name');
            $table->date('cust_birth_date');
            $table->string('cust_mail');
            $table->string('cust_phone');
            $table->string('cust_num');
            $table->string('cust_street');
            $table->string('cust_zip');
            $table->string('cust_city');
            $table->string('cust_country');
            $table->string('cust_num_bill');
            $table->string('cust_street_bill');
            $table->string('cust_zip_bill');
            $table->string('cust_city_bill');
            $table->string('cust_country_bill');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
