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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->string('id', 14)->primary(); // PG-ddmmyy-0000
            $table->char('nuptk', 16)->index();
            $table->foreign('nuptk')->references('nuptk')->on('guru')->onDelete('cascade');
            $table->string('nama', 100);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat_unit_kerja', 255)->nullable();
            $table->enum('jenis_sk', [
                'SK Pengangkatan',
                'SK Pensiun',
                'SK Mutasi',
                'SK Kenaikan Pangkat',
                'SK Kepala Sekolah',
                'SK Guru'
            ]);
            $table->enum('status', ['Menunggu', 'Diterima', 'Diproses', 'Selesai', 'Ditolak'])->default('Menunggu');
            $table->string('file_path', 255)->nullable(); // Path to uploaded SK document
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan')->nullable(); // Notes, e.g., rejection reason
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
