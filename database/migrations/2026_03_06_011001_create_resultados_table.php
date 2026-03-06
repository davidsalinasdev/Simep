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
        Schema::create('resultados', function (Blueprint $table) {

            $table->bigIncrements('id_resultado');

            $table->unsignedBigInteger('id_mesa')->unique();

            $table->unsignedBigInteger('id_usuario');

            $table->timestamp('fecha_envio')->useCurrent();

            $table->text('imagen_acta');

            $table->decimal('latitud', 10, 8)->nullable();

            $table->decimal('longitud', 11, 8)->nullable();

            $table->enum('estado_validacion', ['pendiente', 'validado', 'observado'])->default('pendiente');

            $table->text('observacion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados');
    }
};
