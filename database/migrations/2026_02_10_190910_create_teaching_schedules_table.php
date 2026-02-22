<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teaching_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnDelete();

            $table->foreignUuid('class_id')
                ->constrained('classes')
                ->cascadeOnDelete();

            $table->foreignUuid('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            $table->foreignUuid('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->foreignUuid('day_id')
                ->constrained('days')
                ->cascadeOnDelete();

            // NEW: range period
            $table->foreignUuid('start_period_id')
                ->constrained('lesson_periods')
                ->cascadeOnDelete();

            $table->foreignUuid('end_period_id')
                ->constrained('lesson_periods')
                ->cascadeOnDelete();

            $table->timestamps();

            // Prevent duplicate exact same range for same class
            $table->unique(
                ['academic_year_id', 'class_id', 'day_id', 'start_period_id', 'end_period_id'],
                'unique_class_schedule_range'
            );

            // Prevent teacher double booking with same range
            $table->unique(
                ['academic_year_id', 'teacher_id', 'day_id', 'start_period_id', 'end_period_id'],
                'unique_teacher_schedule_range'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teaching_schedules');
    }
};
