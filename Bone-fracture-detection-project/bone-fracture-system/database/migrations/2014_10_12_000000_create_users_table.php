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
            $table->uuid('id')->primary();
            $table->string('name'); // User's name
            $table->string('email')->unique(); // Unique email
            $table->timestamp('email_verified_at')->nullable(); // Email verification
            $table->string('password'); // Password
            $table->enum('user_type', ['regular_user', 'doctor'])->default('regular_user'); // User type column
            $table->rememberToken(); // Token for "remember me"
            $table->timestamps(); // Created at & Updated at columns
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
