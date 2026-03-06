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
        Schema::create('usuarios', function (Blueprint $table) {

            $table->bigIncrements('id_usuario');

            $table->string('nombre', 150);

            $table->string('ci', 20)->unique();

            $table->string('telefono', 20)->nullable();

            $table->string('email', 150)->unique();

            $table->string('password', 255);

            $table->enum('rol', [
                'super_admin',
                'admin_provincial',
                'admin_municipal',
                'delegado_recinto'
            ]);

            $table->unsignedBigInteger('id_provincia')->nullable();
            $table->unsignedBigInteger('id_municipio')->nullable();
            $table->unsignedBigInteger('id_recinto')->nullable();

            $table->rememberToken();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
