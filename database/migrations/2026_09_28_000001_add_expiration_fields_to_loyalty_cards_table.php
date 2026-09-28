<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loyalty_cards', function (Blueprint $table) {
            $table->date('issued_at')->nullable()->after('card_number');
            $table->date('expires_at')->nullable()->after('issued_at');
            $table->date('last_expired_at')->nullable()->after('expires_at');
        });

        DB::statement('UPDATE loyalty_cards SET issued_at = DATE(COALESCE(created_at, NOW())) WHERE issued_at IS NULL');
        DB::statement('UPDATE loyalty_cards SET expires_at = DATE_ADD(issued_at, INTERVAL 1 YEAR) WHERE expires_at IS NULL');
    }

    public function down()
    {
        Schema::table('loyalty_cards', function (Blueprint $table) {
            $table->dropColumn(['issued_at', 'expires_at', 'last_expired_at']);
        });
    }
};
