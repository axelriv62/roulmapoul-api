<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id('doc_id');
            $table->enum('doc_type', ['type1', 'type2', 'type3']); // Remplacer par les types réels
            $table->string('doc_url');
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
