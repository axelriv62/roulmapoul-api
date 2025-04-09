<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->id('agency_id');
            $table->string('agency_num');
            $table->string('agency_street');
            $table->string('agency_zip');
            $table->string('agency_city');
            $table->string('agency_country');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
