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
        Schema::create('teaching_journals', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('teaching_schedule_id');
            $table->date('date');
            $table->time('start_time')->useCurrent();
            $table->time('end_time')->nullable();
            $table->text('material')->nullable();
            $table->text('activities')->nullable();
            $table->text('assessment')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('teaching_schedule_id')
                ->references('id')
                ->on('teaching_schedules')
                ->cascadeOnDelete();

            $table->unique([
                'teaching_schedule_id',
                'date'
            ], 'unique_journal_per_schedule_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teaching_journals');
    }
};
