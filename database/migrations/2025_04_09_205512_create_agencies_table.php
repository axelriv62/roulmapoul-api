<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('num');
            $table->string('street');
            $table->string('zip');
            $table->string('city');
            $table->string('country');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
