<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Saldo Non Tunai</h2>
    </x-slot>

    <div class="p-4">
        <h3 class="text-lg font-bold">Total Saldo Non Tunai: Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>

        <h4 class="mt-6 text-md font-semibold">Detail Pengeluaran Non Tunai:</h4>
        @foreach ($pengeluarans as $transaksi)
        <div class="mt-4 p-4 border rounded bg-gray-50">
            <div><strong>Tanggal:</strong> {{ $transaksi->tanggal }}</div>
            <div><strong>Nominal:</strong> Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</div>
            <div><strong>Keterangan:</strong> {{ $transaksi->keterangan }}</div>
        </div>
        @endforeach
    </div>
</x-app-layout>