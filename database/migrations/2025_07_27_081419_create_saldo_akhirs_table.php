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
        Schema::create('saldo_akhirs', function (Blueprint $table) {
            $table->id();
            $table->string('periode_bulan'); // Contoh: Juli 2025
            $table->decimal('saldo_tunai', 15, 2)->default(0);
            $table->decimal('saldo_non_tunai', 15, 2)->default(0);

            // rincian uang lembaran
            $table->integer('lembar_100000')->default(0);
            $table->integer('lembar_50000')->default(0);
            $table->integer('lembar_20000')->default(0);
            $table->integer('lembar_10000')->default(0);
            $table->integer('lembar_5000')->default(0);
            $table->integer('lembar_2000')->default(0);
            $table->integer('lembar_1000')->default(0);
            $table->integer('lembar_500')->default(0);
            $table->integer('lembar_200')->default(0);
            $table->integer('lembar_100')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_akhirs');
    }
};
