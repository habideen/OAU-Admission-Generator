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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Faculty');
            $table->string('course');
            $table->string('subject_code_1', 3)->default();
            $table->string('subject_code_2', 3)->default();
            $table->string('subject_code_3', 3)->default();
            $table->string('subject_code_4', 3)->default();
            $table->string('subject_code_5', 3)->default();
            $table->string('subject_code_6', 3)->default();
            $table->string('subject_code_7', 3)->default();
            $table->string('subject_code_8', 3)->default();
            $table->string('disabled', 1)->default();
            $table->unsignedBigInteger('session');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
