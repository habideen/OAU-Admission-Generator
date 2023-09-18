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
        Schema::create('candidates', function (Blueprint $table) {
            $table->string('rg_num', 14);
            $table->string('fullname');
            $table->string('rg_sex', 6);
            $table->string('state_name', 100);
            $table->string('subject_code_1', 3);
            $table->string('subject_code_2', 3);
            $table->string('subject_code_3', 3);
            $table->string('course');
            $table->string('utme_score');
            $table->string('olevel_score');
            $table->string('putme_score');
            $table->string('putme_screening');
            $table->string('aggregate');
            $table->string('session_updated', 9);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
