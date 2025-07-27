<?php

namespace App\Http\Controllers;

use App\Models\KasTransaksi;
use App\Models\UangLembaranTunai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KasTransaksiController extends Controller
{
    public function dashboard()
    {
        $saldoTunai = DB::table('kas_transaksis')
            ->where('jenis_transaksi', 'pemasukan')
            ->where('metode_pembayaran', 'tunai')
            ->sum('nominal') -
            DB::table('kas_transaksis')
            ->where('jenis_transaksi', 'pengeluaran')
            ->where('metode_pembayaran', 'tunai')
            ->sum('nominal');

        $saldoNonTunai = DB::table('kas_transaksis')
            ->where('jenis_transaksi', 'pemasukan')
            ->where('metode_pembayaran', 'non_tunai')
            ->sum('nominal') -
            DB::table('kas_transaksis')
            ->where('jenis_transaksi', 'pengeluaran')
            ->where('metode_pembayaran', 'non_tunai')
            ->sum('nominal');

        return view('dashboard', compact('saldoTunai', 'saldoNonTunai'));
    }

    public function index()
    {
        $kas = KasTransaksi::latest()->paginate(10);
        return view('kas.index', compact('kas'));
    }

    public function create()
    {
        return view('kas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran,penarikan_tunai',
            'sumber_dana' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'nominal' => 'required|numeric',
            'metode_pembayaran' => 'required|in:tunai,non_tunai',
        ]);

        $kas = KasTransaksi::create([
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'jenis_transaksi' => $request->jenis_transaksi,
            'sumber_dana' => $request->sumber_dana,
            'deskripsi' => $request->deskripsi,
            'nominal' => $request->nominal,
            'metode_pembayaran' => $request->metode_pembayaran,
            'created_by' => Auth::id(),
        ]);

        // Jika metode pembayaran tunai dan bukan pengeluaran
        if (in_array($kas->jenis_transaksi, ['pengeluaran', 'penarikan_tunai']) && $kas->metode_pembayaran == 'tunai') {
            $lembaran = [];

            foreach ($request->lembar ?? [] as $nominal => $jumlah) {
                $kolom = 'lembar_' . $nominal;
                $lembaran[$kolom] = $jumlah;
            }

            // Tambahkan foreign key
            $lembaran['kas_transaksi_id'] = $kas->id;

            UangLembaranTunai::create($lembaran);
        }

        return redirect()->route('kas.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $transaksi = KasTransaksi::with('UangLembarans')->findOrFail($id);

        return view('kas.show', compact('transaksi'));
    }

    public function edit(KasTransaksi $kas)
    {
        return view('kas.edit', compact('kas'));
    }

    public function update(Request $request, KasTransaksi $kas)
    {
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran,penarikan_tunai',
            'sumber_dana' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'nominal' => 'required|numeric',
            'metode_pembayaran' => 'required|in:tunai,non_tunai',
        ]);

        $kas->update([
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'jenis_transaksi' => $request->jenis_transaksi,
            'sumber_dana' => $request->sumber_dana,
            'deskripsi' => $request->deskripsi,
            'nominal' => $request->nominal,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        // Hapus uang lembaran lama dan update jika tunai
        if ($kas->metode_pembayaran == 'tunai' && in_array($kas->jenis_transaksi, ['pengeluaran', 'penarikan_tunai'])) {
            $kas->uangLembarans()->delete();

            foreach ($request->uang_lembaran ?? [] as $nominal => $jumlah) {
                if ($jumlah > 0) {
                    UangLembaranTunai::create([
                        'kas_transaksi_id' => $kas->id,
                        'nominal' => $nominal,
                        'jumlah' => $jumlah,
                    ]);
                }
            }
        }

        return redirect()->route('kas.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(KasTransaksi $kas)
    {
        $kas->uangLembarans()->delete(); // Jika ada relasi uang lembaran
        $kas->delete();

        return redirect()->route('kas.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    private function hitungUangPerLembar($jumlah)
    {
        $pecahan = [100000, 50000, 20000, 10000, 5000, 2000, 1000];
        $hasil = [];

        foreach ($pecahan as $nilai) {
            $lembar = intdiv($jumlah, $nilai);
            if ($lembar > 0) {
                $hasil[$nilai] = $lembar;
                $jumlah -= $lembar * $nilai;
            }
        }

        return $hasil;
    }


    public function saldoTunai()
    {
        // Hitung total saldo tunai nominal
        $totalPemasukanTunai = KasTransaksi::where('jenis_transaksi', 'pemasukan')
            ->where('metode_pembayaran', 'tunai')
            ->sum('nominal');

        $totalPenarikanTunai = KasTransaksi::where('jenis_transaksi', 'penarikan_tunai')
            ->sum('nominal');

        $totalPengeluaranTunai = KasTransaksi::where('jenis_transaksi', 'pengeluaran')
            ->where('metode_pembayaran', 'tunai')
            ->sum('nominal');

        $saldoTunai = $totalPemasukanTunai + $totalPenarikanTunai - $totalPengeluaranTunai;

        // Ambil semua transaksi tunai yang memiliki uang lembaran
        $transaksiUangTunai = KasTransaksi::with('uangTunai')
            ->whereIn('jenis_transaksi', ['pemasukan', 'penarikan_tunai', 'pengeluaran'])
            ->where('metode_pembayaran', 'tunai')
            ->get();

        // Hitung lembar uang akhir
        $uangPerLembar = [];

        foreach ($transaksiUangTunai as $transaksi) {
            foreach ($transaksi->uangTunai as $uang) {
                $nilai = $uang->nilai_uang;
                $jumlah = $uang->jumlah;

                // Tambah jika pemasukan atau penarikan_tunai
                if (in_array($transaksi->jenis_transaksi, ['pemasukan', 'penarikan_tunai'])) {
                    if (!isset($uangPerLembar[$nilai])) $uangPerLembar[$nilai] = 0;
                    $uangPerLembar[$nilai] += $jumlah;
                }

                // Kurangi jika pengeluaran
                if ($transaksi->jenis_transaksi == 'pengeluaran') {
                    if (!isset($uangPerLembar[$nilai])) $uangPerLembar[$nilai] = 0;
                    $uangPerLembar[$nilai] -= $jumlah;
                }
            }
        }

        // Filter hanya uang yang jumlahnya positif
        $uangPerLembar = array_filter($uangPerLembar, fn($jml) => $jml > 0);

        return view('saldo.tunai', compact('saldoTunai', 'uangPerLembar'));
    }

    public function saldoNonTunai()
    {
        $totalPemasukanNonTunai = KasTransaksi::where('jenis_transaksi', 'pemasukan')
            ->where('metode_pembayaran', 'non_tunai')
            ->sum('nominal');

        $totalPengeluaranNonTunai = KasTransaksi::where('jenis_transaksi', 'pengeluaran')
            ->where('metode_pembayaran', 'non_tunai')
            ->sum('nominal');

        $totalPenarikanTunai = KasTransaksi::where('jenis_transaksi', 'penarikan_tunai')
            ->sum('nominal');

        $saldoNonTunai = $totalPemasukanNonTunai - $totalPenarikanTunai - $totalPengeluaranNonTunai;

        $transaksiNonTunai = KasTransaksi::where(function ($q) {
            $q->where('jenis_transaksi', 'pengeluaran')
                ->orWhere('jenis_transaksi', 'pemasukan')
                ->orWhere('jenis_transaksi', 'penarikan_tunai');
        })
            ->where('metode_pembayaran', 'non_tunai')
            ->get();

        return view('saldo.nontunai', compact('saldoNonTunai', 'transaksiNonTunai'));
    }
}
