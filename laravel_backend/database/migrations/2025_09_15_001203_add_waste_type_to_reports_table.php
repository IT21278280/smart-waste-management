<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('waste_type', ['organic', 'plastic', 'metal', 'glass', 'hazardous'])->nullable()->after('predicted_label');
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('waste_type');
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending')->change();
        });
    }
};
