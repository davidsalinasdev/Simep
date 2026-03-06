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
        Schema::create('partido_cargo', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('id_partido');

            $table->unsignedBigInteger('id_cargo');

            $table->unsignedBigInteger('id_departamento')->nullable();

            $table->unsignedBigInteger('id_municipio')->nullable();

            $table->foreign('id_partido')->references('id_partido')->on('partidos');

            $table->foreign('id_cargo')->references('id_cargo')->on('cargos');

            $table->foreign('id_departamento')->references('id_departamento')->on('departamentos');

            $table->foreign('id_municipio')->references('id_municipio')->on('municipios');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partido_cargo');
    }
};
