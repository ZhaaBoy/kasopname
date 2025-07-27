<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Saldo Akhir Non Tunai
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('saldo-akhir.nontunai.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="periode_bulan" class="block text-sm font-medium text-gray-700">Periode Bulan</label>
                    <input type="text" name="periode_bulan" id="periode_bulan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Juli 2025" required>
                </div>

                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_awal" class="block text-sm font-medium text-gray-700">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>

                    <div>
                        <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="saldo_non_tunai" class="block text-sm font-medium text-gray-700">Saldo Non Tunai</label>
                    <input type="number" step="0.01" name="saldo_non_tunai" id="saldo_non_tunai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>