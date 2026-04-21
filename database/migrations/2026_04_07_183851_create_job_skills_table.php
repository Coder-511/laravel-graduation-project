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
        Schema::create('job_skills', function (Blueprint $table) {
            $table->foreignId('job_id')
                  ->constrained('jobs', 'job_id')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('skill_id');
            $table->foreign('skill_id')
                  ->references('skill_id')
                  ->on('skills')
                  ->cascadeOnDelete();

            $table->primary(['job_id', 'skill_id']); // composite PK — no duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_skills');
    }
};
