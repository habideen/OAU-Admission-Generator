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
        Schema::create('admission_criterias', function (Blueprint $table) {
            $table->unsignedTinyInteger('id');
            $table->unsignedTinyInteger('merit');
            $table->unsignedTinyInteger('catchment');
            $table->unsignedTinyInteger('elds');
            $table->unsignedTinyInteger('discretion');
            $table->timestamps();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_criterias');
    }
};
