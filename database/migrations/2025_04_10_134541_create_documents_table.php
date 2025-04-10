<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id('id');
            $table->enum('type', ['type1', 'type2', 'type3']); // Remplacer par les types réels
            $table->string('url');
            $table->foreignId('rentals_id')->constrained('rentals')->onDelete('cascade');
            //Si bug à cette table c'est surêment à cause de la ligne au dessus (RETIRER 'S' À RENTALS_ID
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
