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
        Schema::create('log_partnet', function (Blueprint $table) {
        $table->id();
        $table->string('code_partner'); // El código de tarjeta o ID
        $table->string('type_log');     // 'RENOVACION', 'ALTA', etc.
        $table->string('doc')->nullable(); // El DNI o número de afiliación
        $table->string('user');         // Quién lo hizo
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
        Schema::dropIfExists('log_partnet');
    }
};
