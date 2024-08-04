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
        if (Schema::hasTable('request_volunteers')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'request_volunteer_id')) {
                    $table->foreignId('request_volunteer_id')->nullable()->constrained('request_volunteers')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'request_volunteer_id')) {
                $table->dropForeign(['request_volunteer_id']);
                $table->dropColumn('request_volunteer_id');
            }
        });
    }
};
