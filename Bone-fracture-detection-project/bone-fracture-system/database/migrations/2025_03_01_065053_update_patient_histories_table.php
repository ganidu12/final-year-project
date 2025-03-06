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
        Schema::table('patient_histories', function (Blueprint $table) {
            $table->uuid('user_id')->nullable()->change(); // Make user_id nullable
            $table->string('patient_name')->nullable();
            $table->string('patient_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_histories', function (Blueprint $table) {
            $table->uuid('user_id')->nullable(false)->change(); // Revert user_id to NOT NULL
            $table->dropColumn(['patient_name', 'patient_email']); // Drop both columns
        });
    }
};
