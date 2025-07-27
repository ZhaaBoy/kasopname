<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPengeluaranBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kas_transaksi_id',
        'nama_barang',
        'jumlah',
        'harga_satuan',
    ];

    public function transaksi()
    {
        return $this->belongsTo(KasTransaksi::class, 'kas_transaksi_id');
    }

    public function getTotalHargaAttribute()
    {
        return $this->jumlah * $this->harga_satuan;
    }
}
