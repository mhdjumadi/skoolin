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
        Schema::create('attendance_time_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->time('in_start');
            $table->time('in_end');
            $table->time('out_start');
            $table->time('out_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_time_settings');
    }
};
