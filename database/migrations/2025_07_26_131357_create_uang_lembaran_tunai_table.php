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
        Schema::create('uang_lembaran_tunai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kas_transaksi_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('uang_lembaran_tunai');
    }
};
