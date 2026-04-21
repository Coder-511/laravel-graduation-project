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
        Schema::create('seeker_skills', function (Blueprint $table) {
            // NOTE: In Laravel 10, IDs are usually unsignedBigInteger. 
            // If your users.id and skills.skill_id columns are standard INTs, 
            // change these to: $table->unsignedInteger('...')
            $table->unsignedBigInteger('seeker_id');
            $table->unsignedBigInteger('skill_id');

            // Set the composite primary key
            $table->primary(['seeker_id', 'skill_id']);

            // Define the foreign key constraints
            // Assuming your User table's primary key is 'id' (Laravel default)
            $table->foreign('seeker_id')->references('id')->on('users')->onDelete('cascade');
            
            // Your Skill model defines 'skill_id' as the primary key
            $table->foreign('skill_id')->references('skill_id')->on('skills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seeker_skills');
    }
};
