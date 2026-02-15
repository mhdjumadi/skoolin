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
        Schema::create('guardian_students', function (Blueprint $table) {
            $table->uuid('guardian_id')->nullable();
            $table->uuid('student_id')->nullable();
            $table->enum('relationship', ['ayah', 'ibu', 'wali'])->nullable();
            $table->timestamps();

            $table->foreign('guardian_id')
                ->references('id')
                ->on('guardians')
                ->cascadeOnDelete();
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardian_students');
    }
};
