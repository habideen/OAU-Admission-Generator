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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('title', 10); //prof,dr
            $table->string('last_name', 30);
            $table->string('first_name', 30);
            $table->string('middle_name', 30)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_1', 11)->nullable();
            $table->string('phone_2', 11)->nullable();
            $table->rememberToken();
            $table->string('account_type', 15);
            $table->string('faculty_id', 1)->nullable();
            $table->string('account_disabled', 1)->nullable();
            $table->timestamps();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
