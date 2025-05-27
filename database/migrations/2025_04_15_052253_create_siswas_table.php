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
        Schema::create('siswa', function (Blueprint $table) {
            $table->char('nisn', 10)->primary();
            $table->string('nama', 100);
            $table->enum('jenis_kelamin', ["P", "L"]);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->string('alamat', 100);
            $table->enum('status', ["aktif", "nonaktif"])->default("aktif");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
