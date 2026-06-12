<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quality_evaluations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('harvest_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('evaluated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('evaluation_date');

            $table->decimal('aroma_score', 5, 2)->nullable();
            $table->decimal('flavor_score', 5, 2)->nullable();
            $table->decimal('acidity_score', 5, 2)->nullable();
            $table->decimal('body_score', 5, 2)->nullable();
            $table->decimal('sweetness_score', 5, 2)->nullable();

            $table->decimal('final_score', 5, 2);

            $table->enum('quality_grade', [
                'bajo',
                'medio',
                'alto',
                'especial'
            ]);

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_evaluations');
    }
};
