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
        Schema::create('mesas', function (Blueprint $table) {

            $table->bigIncrements('id_mesa');

            $table->integer('numero_mesa');

            $table->unsignedBigInteger('id_recinto');

            $table->enum('estado', ['pendiente', 'enviado'])->default('pendiente');

            $table->foreign('id_recinto')
                ->references('id_recinto')
                ->on('recintos')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
