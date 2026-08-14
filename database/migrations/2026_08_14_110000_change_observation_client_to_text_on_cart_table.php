<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE `cart` MODIFY `observation_client` TEXT NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE `cart` MODIFY `observation_client` VARCHAR(255) NULL');
    }
};
