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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('asset_type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('serial_number')
                ->unique();

            $table->date('purchase_date');

            $table->enum('status', [
                'available',
                'borrowed',
                'under_inspection',
                'maintenance',
                'damaged',
                'retired'
            ])->default('available');

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('status');

            $table->index('asset_type_id');

            $table->index([
                'status',
                'asset_type_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
