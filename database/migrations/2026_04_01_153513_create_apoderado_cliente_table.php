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
       Schema::create('apoderado_cliente', function (Blueprint $table) {
            $table->id('proxy_id');
            // Llave foránea que apunta a la tabla clients
            $table->unsignedBigInteger('proxy_client')->unique();

            $table->string('proxy_pattername')->nullable();
            $table->string('proxy_mattername')->nullable();
            $table->string('proxy_names');
            $table->string('proxy_doc', 20)->nullable();

            // Relación: Si borras al cliente, se borra el apoderado (opcional)
            $table->foreign('proxy_client')
                  ->references('id_client')
                  ->on('clients')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apoderado_cliente');
    }
};
