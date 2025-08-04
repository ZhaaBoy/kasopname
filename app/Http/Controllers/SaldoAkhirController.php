<?php

namespace App\Http\Controllers;

use App\Models\SaldoAkhir;
use Illuminate\Http\Request;
use App\Models\KasTransaksi;
use Barryvdh\DomPDF\Facade\Pdf;

class SaldoAkhirController extends Controller
{
    public function indexTunai()
    {
        $saldos = SaldoAkhir::whereNotNull('saldo_tunai') // hanya data yang saldo_tunai TIDAK null
            ->orderBy('saldo_tunai', 'desc')
            ->paginate(10);

        return view('saldoakhir.index-tunai', compact('saldos'));
    }

    public function indexNonTunai()
    {
        $saldos = SaldoAkhir::whereNotNull('saldo_non_tunai') // hanya data yang saldo_non_tunai TIDAK null
            ->orderBy('saldo_non_tunai', 'desc')
            ->paginate(10);

        return view('saldoakhir.index-non-tunai', compact('saldos'));
    }

    public function cetakPdfTunai($id)
    {
        $saldo = SaldoAkhir::findOrFail($id);

        $pengeluaran = KasTransaksi::where('jenis_transaksi', 'pengeluaran')
            ->where('metode_pembayaran', 'tunai')
            ->whereBetween('tanggal_transaksi', [$saldo->tanggal_awal, $saldo->tanggal_akhir])
            ->orderBy('tanggal_transaksi')
            ->get();

        $pdf = PDF::loadView('saldoakhir.pdf_tunai', compact('saldo', 'pengeluaran'));
        return $pdf->stream("saldo-akhir-{$saldo->id}.pdf");
    }

    public function showTunai($id)
    {
        $saldo = SaldoAkhir::findOrFail($id);
        return view('saldoakhir.show_tunai', compact('saldo'));
    }

    public function showNonTunai($id)
    {
        $saldo = SaldoAkhir::findOrFail($id);
        return view('saldoakhir.show_non_tunai', compact('saldo'));
    }

    public function createTunai()
    {
        return view('saldoakhir.create-tunai');
    }

    public function storeTunai(Request $request)
    {
        $request->validate([
            'periode_bulan' => 'required|string',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        // Ambil tanggal
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // Hitung penarikan tunai (pemasukan ke kas tunai)
        $penarikanTunai = KasTransaksi::where('jenis_transaksi', 'penarikan_tunai')
            ->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])
            ->sum('nominal');

        // Hitung pengeluaran tunai (pengurangan dari kas tunai)
        $pengeluaranTunai = KasTransaksi::where('jenis_transaksi', 'pengeluaran')
            ->where('metode_pembayaran', 'tunai')
            ->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])
            ->sum('nominal');

        // Saldo tunai = penarikan - pengeluaran tunai
        $saldoTunai = $penarikanTunai - $pengeluaranTunai;
        if ($saldoTunai < 0) {
            return back()->withErrors('Saldo non tunai negatif, periksa data transaksi.')->withInput();
        }
        // Simpan ke DB
        SaldoAkhir::create([
            'periode_bulan' => $request->periode_bulan,
            'saldo_tunai' => $saldoTunai,
            'tanggal_awal' => $tanggalAwal,
            'tanggal_akhir' => $tanggalAkhir,
            'saldo_non_tunai' => null,
            'lembar_100000' => $request->input('lembar_100000', 0),
            'lembar_50000' => $request->input('lembar_50000', 0),
            'lembar_20000' => $request->input('lembar_20000', 0),
            'lembar_10000' => $request->input('lembar_10000', 0),
            'lembar_5000' => $request->input('lembar_5000', 0),
            'lembar_2000' => $request->input('lembar_2000', 0),
            'lembar_1000' => $request->input('lembar_1000', 0),
            'lembar_500' => $request->input('lembar_500', 0),
            'lembar_200' => $request->input('lembar_200', 0),
            'lembar_100' => $request->input('lembar_100', 0),
        ]);

        return redirect()->route('saldo-akhir.tunai')->with('success', 'Saldo tunai berhasil ditambahkan.');
    }

    public function getSaldoTunai(Request $request)
    {
        $awal = $request->query('tanggal_awal');
        $akhir = $request->query('tanggal_akhir');

        if (!$awal || !$akhir) {
            return response()->json(['error' => 'Tanggal tidak lengkap'], 400);
        }

        // Hitung total penarikan tunai (masuk ke kas tunai)
        $penarikanTunai = KasTransaksi::where('jenis_transaksi', 'penarikan_tunai')
            ->whereBetween('tanggal_transaksi', [$awal, $akhir])
            ->sum('nominal');

        // Hitung total pengeluaran tunai (keluar dari kas tunai)
        $pengeluaranTunai = KasTransaksi::where('jenis_transaksi', 'pengeluaran')
            ->where('metode_pembayaran', 'tunai')
            ->whereBetween('tanggal_transaksi', [$awal, $akhir])
            ->sum('nominal');

        // Saldo tunai = penarikan - pengeluaran
        $saldo = $penarikanTunai - $pengeluaranTunai;

        return response()->json(['saldo_tunai' => $saldo]);
    }

    public function cetakNonTunai($id)
    {
        $saldo = SaldoAkhir::findOrFail($id);

        $pengeluaran = KasTransaksi::where(function ($query) {
            $query->where('jenis_transaksi', 'pengeluaran')
                ->where('metode_pembayaran', 'non_tunai');
        })
            ->orWhere(function ($query) {
                $query->where('jenis_transaksi', 'penarikan_tunai')
                    ->where('metode_pembayaran', 'tunai');
            })
            ->whereBetween('tanggal_transaksi', [$saldo->tanggal_awal, $saldo->tanggal_akhir])
            ->orderBy('tanggal_transaksi')
            ->get();

        $pdf = PDF::loadView('saldoakhir.pdf_nontunai', compact('saldo', 'pengeluaran'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("saldo-akhir-non-tunai-{$saldo->id}.pdf");
    }

    public function createNonTunai()
    {
        return view('saldoakhir.create-nontunai');
    }

    public function storeNonTunai(Request $request)
    {
        $request->validate([
            'periode_bulan' => 'required|string',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // Hitung pemasukan non tunai
        $pemasukanNonTunai = KasTransaksi::where('jenis_transaksi', 'pemasukan')
            ->where('metode_pembayaran', 'non_tunai')
            ->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])
            ->sum('nominal');

        $pengeluaranNonTunai = KasTransaksi::where(function ($query) use ($tanggalAwal, $tanggalAkhir) {
            $query->where(function ($q) {
                $q->where('jenis_transaksi', 'pengeluaran')
                    ->where('metode_pembayaran', 'non_tunai');
            })
                ->orWhere(function ($q) {
                    $q->where('jenis_transaksi', 'penarikan_tunai')
                        ->where('metode_pembayaran', 'tunai');
                });
        })
            ->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir]) // <- letakkan di luar
            ->sum('nominal');

        $saldoNonTunai = $pemasukanNonTunai - $pengeluaranNonTunai;

        if ($saldoNonTunai < 0) {
            return back()->withErrors('Saldo non tunai negatif, periksa data transaksi.')->withInput();
        }

        SaldoAkhir::create([
            'periode_bulan' => $request->periode_bulan,
            'saldo_non_tunai' => $saldoNonTunai,
            'tanggal_awal' => $tanggalAwal,
            'tanggal_akhir' => $tanggalAkhir,
        ]);

        return redirect()->route('saldo-akhir.non-tunai')->with('success', 'Saldo non tunai berhasil ditambahkan.');
    }

    public function getSaldoNonTunai(Request $request)
    {
        $awal = $request->query('tanggal_awal');
        $akhir = $request->query('tanggal_akhir');

        if (!$awal || !$akhir) {
            return response()->json(['error' => 'Tanggal tidak lengkap'], 400);
        }

        $pemasukan = KasTransaksi::where('jenis_transaksi', 'pemasukan')
            ->where('metode_pembayaran', 'non_tunai')
            ->whereBetween('tanggal_transaksi', [$awal, $akhir])
            ->sum('nominal');

        $pengeluaran = KasTransaksi::where(function ($query) {
            $query->where(function ($q) {
                $q->where('jenis_transaksi', 'pengeluaran')
                    ->where('metode_pembayaran', 'non_tunai');
            })
                ->orWhere(function ($q) {
                    $q->where('jenis_transaksi', 'penarikan_tunai')
                        ->where('metode_pembayaran', 'tunai');
                });
        })
            ->whereBetween('tanggal_transaksi', [$awal, $akhir])
            ->sum('nominal');

        $saldo = $pemasukan - $pengeluaran;

        return response()->json(['saldo_non_tunai' => $saldo]);
    }

    public function destroy($id)
    {
        $saldo = SaldoAkhir::findOrFail($id);
        $saldo->delete();

        return redirect()->back()->with('success', 'Data saldo berhasil dihapus.');
    }
}
