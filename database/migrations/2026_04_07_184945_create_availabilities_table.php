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
        Schema::create('availabilities', function (Blueprint $table) {
            // Primary Key
            $table->id('availability_id'); 
            
            // Foreign Key
            $table->unsignedBigInteger('seeker_id');
            
            // Data Columns
            $table->date('available_date');
            $table->time('available_time');
            
            // Laravel's timestamps() automatically creates `created_at` and `updated_at` 
            // and handles the default CURRENT_TIMESTAMP logic for you.
            $table->timestamps(); 

            // Constraints
            $table->foreign('seeker_id')->references('id')->on('users')->onDelete('cascade');
            
            // Prevents a user from adding the exact same date and time twice
            $table->unique(['seeker_id', 'available_date', 'available_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
