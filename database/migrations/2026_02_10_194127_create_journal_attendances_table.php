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
        Schema::create('journal_attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('teaching_journal_id')
                ->constrained('teaching_journals')
                ->cascadeOnDelete();

            $table->foreignUuid('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('status')->default('hadir');
            $table->string('notes')->nullable();

            $table->timestamps();

            // Prevent duplicate attendance per student per journal
            $table->unique(
                ['teaching_journal_id', 'student_id'],
                'unique_attendance_per_student_journal'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_attendances');
    }
};
