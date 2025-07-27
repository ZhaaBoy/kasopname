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
        Schema::table('kas_transaksis', function (Blueprint $table) {
            $table->dropForeign(['approved_by']); // hapus foreign key
            $table->dropColumn('approved_by');    // hapus kolomnya
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kas_transaksis', function (Blueprint $table) {
            //
        });
    }
};
