<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KasTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_transaksi',
        'jenis_transaksi',
        'sumber_dana',
        'deskripsi',
        'nominal',
        'metode_pembayaran',
        'created_by',
        'approved_by',
    ];

    public function detailBarangs()
    {
        return $this->hasMany(DetailPengeluaranBarang::class);
    }

    public function uangLembarans()
    {
        return $this->hasOne(UangLembaranTunai::class, 'kas_transaksi_id');
    }
    public function uangTunai()
    {
        return $this->hasMany(UangLembaranTunai::class, 'kas_transaksi_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_by');
    }
}
