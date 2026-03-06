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
        Schema::create('votos_partido', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('id_resultado');

            $table->unsignedBigInteger('id_partido_cargo');

            $table->integer('votos');

            $table->foreign('id_resultado')->references('id_resultado')->on('resultados')->onDelete('cascade');

            $table->foreign('id_partido_cargo')->references('id')->on('partido_cargo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votos_partido');
    }
};
