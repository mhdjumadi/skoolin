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
        Schema::create('teaching_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('academic_year_id');
            $table->uuid('class_id');
            $table->uuid('teacher_id');
            $table->uuid('subject_id');
            $table->uuid('lesson_period_id');
            $table->uuid('day_id');

            $table->timestamps();

            // Foreign Keys
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->cascadeOnDelete();;
            $table->foreign('class_id')->references('id')->on('classes')->cascadeOnDelete();;
            $table->foreign('teacher_id')->references('id')->on('teachers')->cascadeOnDelete();;
            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();;
            $table->foreign('lesson_period_id')->references('id')->on('lesson_periods')->cascadeOnDelete();;
            $table->foreign('day_id')->references('id')->on('days')->cascadeOnDelete();;

            // Prevent duplicate schedules
            $table->unique([
                'academic_year_id',
                'class_id',
                'day_id',
                'lesson_period_id'
            ], 'unique_class_schedule');
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
