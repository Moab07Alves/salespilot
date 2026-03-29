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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->morphs('addressable');
            $table->string('type')->default('other');
            $table->foreignId('city_id')->constrained();
            $table->string('district')->nullable();
            $table->string('street');
            $table->string('zip_code', 10);
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
            $table->index('city_id');
            $table->index('zip_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
