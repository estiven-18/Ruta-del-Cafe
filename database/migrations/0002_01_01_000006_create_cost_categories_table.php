<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        //tabla para categorizar los costos, ej: fertilizantes, mano de obra, maquinaria, etc
        Schema::create('cost_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cost_categories');
    }
};
