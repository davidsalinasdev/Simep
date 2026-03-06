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
        Schema::create('votos_especiales', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('id_resultado')->unique();

            $table->integer('blancos');

            $table->integer('nulos');

            $table->integer('total_papeletas');

            $table->foreign('id_resultado')
                ->references('id_resultado')
                ->on('resultados')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votos_especiales');
    }
};
