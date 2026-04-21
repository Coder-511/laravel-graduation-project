<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('job_shifts', function (Blueprint $table) {
            $table->id('shift_id');

            $table->foreignId('job_id')
                  ->constrained('jobs', 'job_id')
                  ->cascadeOnDelete();

            $table->date('shift_date');
            $table->time('shift_start');
            $table->time('shift_end');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('job_shifts');
    }
};
