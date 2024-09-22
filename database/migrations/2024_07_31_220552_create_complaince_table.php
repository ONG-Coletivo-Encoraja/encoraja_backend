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
        Schema::create('complainces', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 50);
            $table->string('phone_number', 14);
            $table->text('description');
            $table->string('relation', 255);
            $table->string('motivation', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complainces');
    }
};
