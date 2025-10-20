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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('image_path');
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->string('predicted_label')->nullable();
            $table->float('confidence')->nullable();
            $table->enum('status', ['pending', 'assigned', 'collected', 'rejected'])->default('pending');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['predicted_label']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
