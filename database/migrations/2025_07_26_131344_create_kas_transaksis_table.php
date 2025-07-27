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
        Schema::create('kas_transaksis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_transaksi');
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran', 'penarikan_tunai']);
            $table->enum('sumber_dana', ['tunai', 'non_tunai']);
            $table->string('deskripsi')->nullable();
            $table->bigInteger('nominal');
            $table->enum('metode_pembayaran', ['tunai', 'non_tunai'])->nullable(); // redundant dengan sumber_dana tapi fleksibel
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_transaksis');
    }
};
