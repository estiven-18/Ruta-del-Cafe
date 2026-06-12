<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harvest_costs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('harvest_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('cost_category_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            //estoo es lo que se gastó en esta categoría para esta cosecha    
            $table->decimal('amount', 14, 2);


            //estp es para saber cuando fue el gasto
            $table->date('incurred_date');

            $table->text('description')->nullable();

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

            $table->index('harvest_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harvest_costs');
    }
};
