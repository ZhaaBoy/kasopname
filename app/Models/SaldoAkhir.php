<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaldoAkhir extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode_bulan',
        'tanggal_awal',
        'tanggal_akhir',
        'saldo_tunai',
        'saldo_non_tunai',
        'lembar_100000',
        'lembar_50000',
        'lembar_20000',
        'lembar_10000',
        'lembar_5000',
        'lembar_2000',
        'lembar_1000',
        'lembar_500',
        'lembar_200',
        'lembar_100',
    ];
}
