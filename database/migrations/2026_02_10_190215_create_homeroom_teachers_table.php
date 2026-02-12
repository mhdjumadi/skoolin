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
        Schema::create('homeroom_teachers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            $table->foreignUuid('class_id')
                ->constrained('classes')
                ->cascadeOnDelete();

            $table->foreignUuid('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnDelete();

            $table->timestamps();

            // 1 kelas hanya boleh punya 1 wali per tahun ajaran
            $table->unique(['class_id', 'academic_year_id']);

            // 1 guru hanya boleh jadi wali 1 kelas per tahun ajaran (opsional)
            $table->unique(['teacher_id', 'academic_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homeroom_teachers');
    }
};
