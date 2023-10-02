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
        Schema::create('admission_lists', function (Blueprint $table) {
            $table->string('rg_num', 15);
            $table->string('category', 15);
            $table->string('course');
            $table->timestamps();

            $table->primary('rg_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_lists');
    }
};
