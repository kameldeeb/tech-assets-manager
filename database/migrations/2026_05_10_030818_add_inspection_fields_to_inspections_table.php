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
        Schema::table('inspections', function (Blueprint $table) {
            $table->enum('verified_condition', [
                'excellent',
                'good',
                'fair',
                'needs_repair'
            ])->nullable();
            $table->enum('new_status', [
                'available',
                'maintenance',
                'retired'
            ])->nullable();
            $table->timestamp('completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn(['verified_condition', 'new_status', 'completed_at']);
        });
    }
};
