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
        Schema::create('request_volunteers', function (Blueprint $table) {
            $table->id();
            $table->string('availability', 100)->nullable();
            $table->text('course_experience')->nullable();
            $table->text('how_know')->nullable();
            $table->text('expectations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_volunteer');
    }
};
