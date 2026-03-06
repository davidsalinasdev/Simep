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
        Schema::create('recintos', function (Blueprint $table) {

            $table->bigIncrements('id_recinto');

            $table->string('nombre');

            $table->string('direccion')->nullable();

            $table->unsignedBigInteger('id_localidad');

            $table->foreign('id_localidad')
                ->references('id_localidad')
                ->on('localidades')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recintos');
    }
};
