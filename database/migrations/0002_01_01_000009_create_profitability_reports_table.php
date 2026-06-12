<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profitability_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('harvest_id')
                ->unique()
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            //ingresos totales de la cosecha, calculados a partir del peso neto y el precio por kg
            $table->decimal('total_income', 14, 2);

            //costos totales asociados a la cosecha, sumando todos los costos registrados en harvest_costs
            $table->decimal('total_costs', 14, 2);

            //ganancia neta, calculada como ingresos totales menos costos totales
            $table->decimal('net_profit', 14, 2);

            //rentabilidad, calculada como (ganancia neta / costos totales) * 100 para obtener un porcentaje
            $table->decimal('profitability_percentage', 8, 2);

            $table->timestamp('calculated_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profitability_reports');
    }
};
