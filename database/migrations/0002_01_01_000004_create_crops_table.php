<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crops', function (Blueprint $table) {
            $table->id();

            $table->foreignId('farm_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('coffee_variety_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();


            $table->date('planting_date');

            $table->date('estimated_harvest_date')->nullable();

            $table->decimal('area_hectares', 10, 2);

            $table->integer('plant_count')->nullable();

            $table->enum('status', [
                'activo',
                'cosechado',
                'abandonado'
            ])->default('activo');

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('farm_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crops');
    }
};
