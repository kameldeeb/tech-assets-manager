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
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('result', [
                'excellent',
                'good',
                'damaged',
                'maintenance_required'
            ]);

            $table->text('notes')
                ->nullable();

            $table->timestamp('inspected_at');

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
