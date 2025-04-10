<?php

use App\Models\Rental;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id('id');
            $table->enum('type', ['type1', 'type2', 'type3']); //TODO Remplacer par les types réels
            $table->string('url');
            $table->foreignIdfor(Rental::class)->constrained('rentals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
