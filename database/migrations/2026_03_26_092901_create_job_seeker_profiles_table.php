<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('job_seeker_profiles', function (Blueprint $table) {
            $table->foreignId('seeker_id')
                  ->primary()
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('city', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('job_seeker_profiles');
    }
};
