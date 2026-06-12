<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producer_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('name');

            $table->text('location');

            $table->decimal('total_area_hectares', 10, 2);

            $table->enum('status', [
                'activo',
                'inactivo',
                'archivado'
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

            $table->index('producer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farms');
    }
};
