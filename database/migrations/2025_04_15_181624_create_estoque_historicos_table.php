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
        Schema::create('estoque_historicos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('estoque_id')->constrained()->onDelete('cascade');
                $table->integer('quantidade_anterior');
                $table->integer('quantidade_utilizada');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estoque_historicos');
    }
};
