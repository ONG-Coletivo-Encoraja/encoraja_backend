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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->date('date');
            $table->time('time');
            $table->enum('modality', ['Presential', 'Hybrid', 'Remote']);
            $table->enum('status', ['Active', 'Inactive', 'Pending'])->default('Pending');
            $table->enum('type', ['Course', 'Workshop', 'Lecture']);
            $table->string('target_audience');
            $table->integer('vacancies');
            $table->integer('social_vacancies')->nullable();
            $table->integer('regular_vacancies')->nullable();
            $table->text('material')->nullable();
            $table->string('interest_area');
            $table->decimal('price', 8, 2)->default(0.00);
            $table->integer('workload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
