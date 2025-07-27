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
        Schema::table('saldo_akhirs', function (Blueprint $table) {
            $table->date('tanggal_awal')->nullable()->after('periode_bulan');
            $table->date('tanggal_akhir')->nullable()->after('tanggal_awal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saldo_akhirs', function (Blueprint $table) {
            //
        });
    }
};
