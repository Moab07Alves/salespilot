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
        Schema::create('product_variant_supplier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('supplier_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->decimal('cost_price', 12, 2);
            $table->string('supplier_sku')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->unique(['product_variant_id', 'supplier_id']);
            $table->index('product_variant_id');
            $table->index('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_supplier');
    }
};
