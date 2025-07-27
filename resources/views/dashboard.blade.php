<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kas Opname') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Saldo Tunai -->
                <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-green-500">
                    <h3 class="text-lg font-semibold text-gray-700">Saldo Tunai</h3>
                    <p class="mt-2 text-2xl font-bold text-green-600">
                        Rp {{ number_format($saldoTunai, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Saldo Non Tunai -->
                <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-blue-500">
                    <h3 class="text-lg font-semibold text-gray-700">Saldo Non Tunai</h3>
                    <p class="mt-2 text-2xl font-bold text-blue-600">
                        Rp {{ number_format($saldoNonTunai, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Total Pengeluaran -->
                <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-red-500">
                    <h3 class="text-lg font-semibold text-gray-700">Total Pengeluaran</h3>
                    <p class="mt-2 text-2xl font-bold text-red-600">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="mt-10">
                <div class="bg-white shadow rounded-xl p-6">
                    <h4 class="text-lg font-bold text-gray-800 mb-4">Ringkasan</h4>
                    <p class="text-gray-600">
                        Dashboard ini menampilkan saldo kas tunai dan non tunai secara real-time berdasarkan transaksi pemasukan dan pengeluaran. Silakan kelola data pada menu transaksi untuk memperbarui saldo.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>