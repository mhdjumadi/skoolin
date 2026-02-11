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
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('rfid')->unique()->nullable();
            $table->string('nisn')->unique()->nullable();
            $table->string('name');
            $table->enum('gender', ['l', 'p'])->nullable();
            $table->string('birth_place');
            $table->date('birth_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('address');
            $table->boolean('is_active')->default(true);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
