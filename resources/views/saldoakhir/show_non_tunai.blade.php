<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Saldo Akhir Non Tunai</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-lg font-semibold">Periode: {{ $saldo->periode_bulan }}</p>
                        <p class="text-lg">Saldo Non Tunai:
                            <span class="font-bold text-green-600">Rp {{ number_format($saldo->saldo_non_tunai, 0, ',', '.') }}</span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('saldo-akhir.cetakNonTunai', $saldo->id) }}" target="_blank"
                            class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                            Cetak PDF
                        </a>
                    </div>
                </div>


                <div class="mt-6">
                    <a href="{{ route('saldo-akhir.non-tunai') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold px-4 py-2 rounded">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>