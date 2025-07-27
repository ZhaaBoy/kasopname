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
        $saldos = SaldoAkhir::where('saldo_tunai', '>', 0)->orderBy('periode_bulan', 'desc')->paginate(10);
        return view('saldoakhir.index-tunai', compact('saldos'));
    }

    public function indexNonTunai()
    {
        $saldos = SaldoAkhir::where('saldo_non_tunai', '>', 0)->orderBy('periode_bulan', 'desc')->paginate(10);
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
            'saldo_tunai' => 'required|numeric',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        SaldoAkhir::create([
            'periode_bulan' => $request->periode_bulan,
            'saldo_tunai' => $request->saldo_tunai,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
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

    public function cetakNonTunai($id)
    {
        $saldo = SaldoAkhir::findOrFail($id);

        $pengeluaran = KasTransaksi::where('jenis_transaksi', 'pengeluaran')
            ->where('metode_pembayaran', 'non_tunai')
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
            'saldo_non_tunai' => 'required|numeric',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        SaldoAkhir::create([
            'periode_bulan' => $request->periode_bulan,
            'saldo_non_tunai' => $request->saldo_non_tunai,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
        ]);

        return redirect()->route('saldo-akhir.non-tunai')->with('success', 'Saldo non tunai berhasil ditambahkan.');
    }
    public function destroy($id)
    {
        $saldo = SaldoAkhir::findOrFail($id);
        $saldo->delete();

        return redirect()->back()->with('success', 'Data saldo berhasil dihapus.');
    }
}
