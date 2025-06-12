<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('uso_diarios', function (Blueprint $table) {
            $table->id();
            $table->text('observacoes')->nullable();
            $table->foreignId('cultivo_id')->constrained()->onDelete('cascade');
            $table->foreignId('viveiro_id')->constrained()->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('products')->onDelete('cascade');
            $table->date('data');
            $table->decimal('quantidade_utilizada', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('uso_diarios');
    }
};

