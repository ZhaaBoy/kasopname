<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Saldo Akhir Tunai') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Saldo akhir tunai --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-2">Total Saldo Akhir Tunai:</h3>
                <p class="text-2xl text-green-600 font-semibold">
                    Rp{{ number_format($saldoTunai, 0, ',', '.') }}
                </p>
            </div>

            {{-- Detail pengeluaran tunai --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">Detail Pengeluaran Tunai</h3>
                <table class="table-auto w-full border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Tanggal</th>
                            <th class="px-4 py-2 border">Keterangan</th>
                            <th class="px-4 py-2 border">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($totalPengeluaranTunai as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item->tanggal }}</td>
                            <td class="border px-4 py-2">{{ $item->keterangan }}</td>
                            <td class="border px-4 py-2">Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        </tr>

                        {{-- Jika ada uang lembaran --}}
                        @if ($item->uangTunai && $item->uangTunai->count())
                        <tr>
                            <td colspan="3" class="border px-4 py-2">
                                <strong>Uang Lembaran:</strong>
                                <ul class="list-disc pl-5">
                                    @foreach ($item->uangTunai as $lembar)
                                    <li>{{ $lembar->pecahan }} x {{ $lembar->jumlah }} = Rp{{ number_format($lembar->pecahan * $lembar->jumlah, 0, ',', '.') }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>