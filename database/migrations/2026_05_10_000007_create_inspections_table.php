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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('asset_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('loan_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('inspected_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('result', [
                'pending',
                'excellent',
                'good',
                'damaged',
                'maintenance_required'
            ])->default('pending');

            $table->enum('verified_condition', [
                'excellent',
                'good',
                'fair',
                'needs_repair'
            ])->nullable();

            $table->enum('new_status', [
                'available',
                'borrowed',
                'under_inspection',
                'damaged'
            ])->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamp('inspected_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
