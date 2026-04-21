<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id('application_id');

            $table->foreignId('job_id')
                  ->constrained('jobs', 'job_id')   // jobs.job_id is a named PK
                  ->cascadeOnDelete();

            $table->foreignId('seeker_id')
                  ->constrained('users')             // users.id (default)
                  ->cascadeOnDelete();

            $table->enum('status', ['Pending', 'Accepted', 'Rejected', 'Canceled'])
                  ->default('Pending');

            $table->decimal('match_score', 5, 2)->nullable();
            $table->text('message')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['job_id', 'seeker_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};