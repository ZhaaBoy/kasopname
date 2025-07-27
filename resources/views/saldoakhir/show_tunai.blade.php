<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Saldo Akhir Tunai
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-lg font-semibold">Periode Bulan: {{ $saldo->periode_bulan }}</p>

                    @if ($saldo->tanggal_awal && $saldo->tanggal_akhir)
                    <p class="text-sm text-gray-600">
                        ({{ \Carbon\Carbon::parse($saldo->tanggal_awal)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($saldo->tanggal_akhir)->format('d M Y') }})
                    </p>
                    @endif

                    <p class="text-lg">Saldo Tunai:
                        <span class="font-bold text-green-600">Rp {{ number_format($saldo->saldo_tunai, 0, ',', '.') }}</span>
                    </p>
                </div>
                <div>
                    <a href="{{ route('saldo-akhir.pdf.tunai', $saldo->id) }}" target="_blank"
                        class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        Cetak PDF
                    </a>
                </div>
            </div>

            <hr class="my-4">

            <h3 class="text-md font-bold mb-2">Rincian Uang Lembaran:</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach ([100000, 50000, 20000, 10000, 5000, 2000, 1000, 500, 200, 100] as $nilai)
                <div class="bg-gray-100 p-3 rounded shadow-sm">
                    <p class="font-medium">Rp {{ number_format($nilai, 0, ',', '.') }}</p>
                    <p class="text-gray-700">Lembar: {{ $saldo['lembar_'.$nilai] }}</p>
                    <p class="text-sm text-gray-500">Total: Rp {{ number_format($saldo['lembar_'.$nilai] * $nilai, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
            <div class="mt-6">
                <a href="{{ route('saldo-akhir.tunai') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold px-4 py-2 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-app-layout>