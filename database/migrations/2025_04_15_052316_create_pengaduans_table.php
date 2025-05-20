<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->string('id', 12)->primary(); // PID-YYYY-NNN
            $table->char('npsn', 8);
            $table->foreign('npsn')->references('npsn')->on('sekolah')->onDelete('cascade');
            $table->string('judul', 100);
            $table->text('deskripsi');
            $table->enum('kategori', ['Kendala Teknis', 'Pelayanan', 'Lainnya']);
            $table->string('bukti_path', 255)->nullable(); // Path to uploaded image
            $table->enum('status', ['Menunggu', 'Diproses', 'Selesai'])->default('Menunggu');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan')->nullable(); // Notes, e.g., resolution details
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};