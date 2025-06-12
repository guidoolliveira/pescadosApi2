<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cultivos', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(1);
            $table->integer('quantidade_camarao');
            $table->foreignId('viveiro_id')->constrained()->onDelete('cascade');
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('cultivos');
    }
};

