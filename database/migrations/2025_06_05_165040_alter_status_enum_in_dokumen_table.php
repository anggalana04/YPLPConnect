<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE dokumen MODIFY COLUMN status ENUM('Menunggu', 'Diterima', 'Diproses', 'Selesai', 'Ditolak') NOT NULL DEFAULT 'Menunggu'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE dokumen MODIFY COLUMN status ENUM('Menunggu', 'Disetujui', 'Ditolak') NOT NULL DEFAULT 'Menunggu'");
    }
};
