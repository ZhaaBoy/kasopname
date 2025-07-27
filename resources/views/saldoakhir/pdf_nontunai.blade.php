<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Saldo Akhir Non Tunai</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 10px;
        }

        .kop-surat h2,
        .kop-surat h4 {
            margin: 0;
        }

        .kop-surat p {
            margin: 2px;
        }

        .garis {
            border-bottom: 3px solid black;
            margin-bottom: 20px;
        }

        .meta,
        .ttd {
            margin-top: 40px;
            width: 100%;
        }

        .ttd p {
            margin-bottom: 60px;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <h2>SEKOLAH DASAR NEGERI CARINGIN</h2>
        <p>Jl. Raya Caringin No. 123, Kecamatan Balaraja, Kabupaten Tangerang</p>
        <p>Email: sdn.caringin@example.com | Telp: (021) 1234567</p>
    </div>
    <div class="garis"></div>

    <h3 style="text-align:center; text-decoration: underline;">LAPORAN SALDO AKHIR NON TUNAI</h3>
    <p style="text-align:center;">Periode: {{ $saldo->periode_bulan }}</p>

    <div class="meta">
        <p><strong>Saldo Non Tunai:</strong> Rp {{ number_format($saldo->saldo_non_tunai, 0, ',', '.') }}</p>
    </div>

    <h4>Rincian Pengeluaran Non Tunai</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>sumber_dana</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPengeluaran = 0; @endphp
            @forelse($pengeluaran as $index => $item)
            @php $totalPengeluaran += $item->nominal; @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->deskripsi ?? '-' }}</td>
                <td>{{ $item->sumber_dana }}</td>
                <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;">Tidak ada pengeluaran non tunai.</td>
            </tr>
            @endforelse
            <tr>
                <td colspan="4"><strong>Total Pengeluaran</strong></td>
                <td><strong>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="ttd">
        <p>Tangerang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p><strong>Kepala Sekolah</strong></p>

    </div>
</body>

</html>