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
        Schema::create('subject_combinations', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('subject_code_1', 3)->default();
            $table->string('subject_code_2', 3)->default();
            $table->string('subject_code_3', 3)->default();
            $table->string('subject_code_4', 3)->default();
            $table->string('subject_code_5', 3)->default();
            $table->string('subject_code_6', 3)->default();
            $table->string('subject_code_7', 3)->default();
            $table->string('subject_code_8', 3)->default();
            $table->string('session_updated', 9);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_combinations');
    }
};
