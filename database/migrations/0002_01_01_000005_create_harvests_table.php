<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harvests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('crop_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();


            $table->date('harvest_date');

            //esto es lo que se cosechó, antes de quitar los defectuosos
            $table->decimal('gross_weight_kg', 12, 2);

            //lo que se quitó por ser defectuoso
            $table->decimal('defective_weight_kg', 12, 2)
                ->default(0);

            //lo que quedó después de quitar los defectuosos    
            $table->decimal('net_weight_kg', 12, 2);

            //precio de venta por kg, para calcular ingresos
            $table->decimal('sale_price_per_kg', 14, 2);

            //ingresos totales por esta cosecha
            $table->decimal('total_income', 14, 2);

            $table->enum('status', [
                'borrador',
                'completado',
                'cancelado'
            ])->default('borrador');

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('harvest_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harvests');
    }
};
