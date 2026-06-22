<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_socio', function (Blueprint $table) {
        $table->id();

        // Relación con el Cliente (1 a 1)
        $table->unsignedBigInteger('client_id');
        $table->foreign('client_id')->references('id_client')->on('clients')->onDelete('cascade');

        // Relación con el Apoderado (N a 1: Varios socios pueden tener el mismo apoderado)
        $table->unsignedBigInteger('proxy_id')->nullable();
        $table->foreign('proxy_id')->references('proxy_id')->on('apoderado_cliente');

        // Campos de la tarjeta y estado
        $table->string('nTarjNumb', 20)->unique();
        $table->tinyInteger('cTarjActi')->default(1);
        $table->date('dEmisDate')->nullable();
        $table->date('dCaduDate')->nullable();
        $table->tinyInteger('validado')->default(0);
        $table->string('affiliation')->nullable();
        $table->integer('status_magic')->default(0);

        // Campos de auditoría y contacto
        $table->string('user_new')->nullable();
        $table->string('user_renew')->nullable();
        $table->string('phone_number')->nullable();
        $table->string('confirmation_email')->nullable();

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_socio');
    }
};
