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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // Product snapshot
            $table->string('product_name');
            $table->string('product_slug');
            $table->string('product_image')->nullable();

            // Price snapshot
            $table->decimal('price', 10, 2);
            $table->integer('discount')->nullable();
            $table->decimal('final_price', 10, 2);
            $table->integer('quantity');

            // Prescription data
            $table->string('prescription_image')->nullable();
            $table->json('right_eye')->nullable();
            $table->json('left_eye')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
