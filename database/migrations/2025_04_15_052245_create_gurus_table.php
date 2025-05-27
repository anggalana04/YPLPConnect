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
        Schema::create('guru', function (Blueprint $table) {
            $table->char('NUPTK', 16)->primary();
            $table->string('nama', 100);
            $table->enum('jenis_kelamin', ["P", "L"]);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->string('alamat', 100);
            $table->string('no_hp', 13);
            $table->enum('status', ["aktif", "nonaktif"])->default("aktif");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
