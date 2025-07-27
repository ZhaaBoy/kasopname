<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Transaksi
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6">
                <h3 class="text-lg font-bold mb-4">Informasi Transaksi</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><strong>Tanggal:</strong> {{ $transaksi->tanggal_transaksi }}</div>
                    <div><strong>Jenis:</strong> {{ ucwords(str_replace('_', ' ', $transaksi->jenis_transaksi)) }}</div>
                    <div><strong>Nominal:</strong> Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</div>
                    <div><strong>Metode Pembayaran:</strong> {{ ucfirst($transaksi->metode_pembayaran) }}</div>
                    <div class="col-span-2"><strong>Deskripsi:</strong> {{ $transaksi->deskripsi }}</div>
                </div>

                @if ($transaksi->metode_pembayaran === 'tunai' && $transaksi->UangLembarans)
                <h2 class="text-lg font-semibold mt-4 mb-2">Rincian Uang Tunai</h2>
                <table class="table-auto w-full border border-gray-300 rounded-md shadow-md">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Pecahan</th>
                            <th class="px-4 py-2 text-left">Jumlah Lembar</th>
                            <th class="px-4 py-2 text-left">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $pecahanList = [
                        100000, 50000, 20000, 10000,
                        5000, 2000, 1000, 500, 200, 100
                        ];
                        $totalTunai = 0;
                        @endphp
                        @foreach ($pecahanList as $pecahan)
                        @php
                        $jumlahLembar = $transaksi->UangLembarans->{'lembar_'.$pecahan} ?? 0;
                        $subtotal = $pecahan * $jumlahLembar;
                        $totalTunai += $subtotal;
                        @endphp
                        @if ($jumlahLembar > 0)
                        <tr class="border-t border-gray-200">
                            <td class="px-4 py-2">Rp {{ number_format($pecahan, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $jumlahLembar }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @endforeach
                        <tr class="bg-gray-50 font-bold border-t border-gray-300">
                            <td class="px-4 py-2" colspan="2">Total Tunai</td>
                            <td class="px-4 py-2">Rp {{ number_format($totalTunai, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>