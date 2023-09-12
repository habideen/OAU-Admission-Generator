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
            $table->string('subject_1', 3);
            $table->string('subject_2', 3);
            $table->string('subject_3', 3);
            $table->string('subject_4', 3);
            $table->string('subject_5', 3);
            $table->string('subject_6', 3);
            $table->string('subject_7', 3);
            $table->string('subject_8', 3);
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
