<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farm_producer', function (Blueprint $table) {
            $table->foreignId('farm_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('producer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unique(['farm_id', 'producer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farm_producer');
    }
};
