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
        Schema::create('biometrias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float("shrimp_weight");
            $table->float("weight");
            $table->integer("quantity");
            $table->date("date");
            $table->text('description');
            $table->string('image')->nullable();
            $table->foreignId('viveiro_id')->constrained('viveiros')->onDelete('cascade');
            $table->foreignId('cultivo_id')->constrained('cultivos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biometrias');
    }
};
