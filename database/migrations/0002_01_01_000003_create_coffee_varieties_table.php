<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coffee_varieties', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();

            $table->string('scientific_name')->nullable();

            $table->text('description')->nullable();

            $table->integer('typical_maturity_months')->nullable();

            $table->boolean('is_resistant')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coffee_varieties');
    }
};
