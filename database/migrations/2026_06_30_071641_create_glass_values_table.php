<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('glass_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('glass_id')
                ->constrained('glasses')
                ->cascadeOnDelete();

            $table->string('value');
            $table->integer('price');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glass_values');
    }
};
