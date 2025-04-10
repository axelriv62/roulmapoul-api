<?php

use App\Models\Rental;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('amendments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('price');
            $table->string('content');
            $table->foreignIdFor(Rental::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amendments');
    }
};
