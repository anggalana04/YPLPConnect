<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE pengaduan MODIFY status ENUM('menunggu', 'diterima', 'diproses', 'selesai') NOT NULL DEFAULT 'menunggu'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE pengaduan MODIFY status ENUM('menunggu', 'diproses', 'selesai') NOT NULL DEFAULT 'menunggu'");
    }

};
