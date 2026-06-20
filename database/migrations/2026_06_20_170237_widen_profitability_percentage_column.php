<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profitability_reports', function (Blueprint $table) {
            $table->decimal('profitability_percentage', 12, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('profitability_reports', function (Blueprint $table) {
            $table->decimal('profitability_percentage', 8, 2)->change();
        });
    }
};
