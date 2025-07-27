<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangLembaranTunai extends Model
{
    use HasFactory;

    protected $table = 'uang_lembaran_tunais';

    protected $fillable = [
        'kas_transaksi_id',
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

    public function kasTransaksi()
    {
        return $this->belongsTo(KasTransaksi::class, 'kas_transaksi_id');
    }
}
