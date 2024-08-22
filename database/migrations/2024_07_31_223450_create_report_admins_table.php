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
        Schema::create('report_admins', function (Blueprint $table) {
            $table->id();
            $table->integer('qtt_person');
            $table->text('description');
            $table->text('results');
            $table->text('observation')->nullable();
            $table->foreignId('relates_event_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_admins');
    }
};
