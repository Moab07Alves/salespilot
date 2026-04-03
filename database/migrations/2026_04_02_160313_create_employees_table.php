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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('position_id')
                  ->constrained()
                  ->restrictOnDelete();
            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained()
                  ->restrictOnDelete();
            $table->foreignId('manager_id')
                  ->nullable()
                  ->constrained('employees')
                  ->nullOnDelete();
            $table->string('document', 14)->unique();
            $table->date('birth_date');
            $table->string('phone', 15);
            $table->date('hire_date');
            $table->date('termination_date')->nullable();
            $table->decimal('salary', 10, 2);
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->index('position_id');
            $table->index('department_id');
            $table->index('manager_id');
            $table->index('status');
            $table->index('hire_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
