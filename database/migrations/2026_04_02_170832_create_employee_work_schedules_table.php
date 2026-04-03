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
        Schema::create('employee_work_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('work_schedule_id')
                  ->constrained()
                  ->restrictOnDelete();
            $table->tinyInteger('day_of_week');
            $table->timestamps();
            $table->unique(['employee_id', 'day_of_week']);
            $table->index('day_of_week');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_work_schedules');
    }
};
