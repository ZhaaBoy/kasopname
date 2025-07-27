<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\KasTransaksi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total pemasukan tunai
        $totalPemasukanTunai = KasTransaksi::where('jenis_transaksi', 'pemasukan')
            ->where('metode_pembayaran', 'tunai')
            ->sum('nominal');

        // Total pemasukan non tunai
        $totalPemasukanNonTunai = KasTransaksi::where('jenis_transaksi', 'pemasukan')
            ->where('metode_pembayaran', 'non_tunai')
            ->sum('nominal');

        // Total pengeluaran tunai (hanya 'pengeluaran' yg tunai)
        $totalPengeluaranTunai = KasTransaksi::where('jenis_transaksi', 'pengeluaran')
            ->where('metode_pembayaran', 'tunai')
            ->sum('nominal');

        // Total pengeluaran non tunai
        $totalPengeluaranNonTunai = KasTransaksi::where('jenis_transaksi', 'pengeluaran')
            ->where('metode_pembayaran', 'non_tunai')
            ->sum('nominal');

        // Total penarikan tunai (mengurangi saldo non tunai, menambah saldo tunai)
        $totalPenarikanTunai = KasTransaksi::where('jenis_transaksi', 'penarikan_tunai')
            ->sum('nominal');

        // Kalkulasi saldo
        $saldoTunai = $totalPemasukanTunai + $totalPenarikanTunai - $totalPengeluaranTunai;
        $saldoNonTunai = $totalPemasukanNonTunai - $totalPenarikanTunai - $totalPengeluaranNonTunai;

        $totalPengeluaran = $totalPengeluaranTunai + $totalPengeluaranNonTunai;

        return view('dashboard', compact(
            'saldoTunai',
            'saldoNonTunai',
            'totalPengeluaran'
        ));
    }
}
