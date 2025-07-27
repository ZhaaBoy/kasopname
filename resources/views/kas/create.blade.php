<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Transaksi Kas') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('kas.store') }}" method="POST">
                    @csrf

                    <!-- Tanggal -->
                    <div class="mb-4">
                        <x-label for="tanggal_transaksi" :value="__('Tanggal Transaksi')" />
                        <x-input id="tanggal_transaksi" class="block mt-1 w-full" type="date" name="tanggal_transaksi" required />
                    </div>

                    <!-- Jenis Transaksi -->
                    <div class="mb-4">
                        <x-label for="jenis_transaksi" :value="__('Jenis Transaksi')" />
                        <select name="jenis_transaksi" id="jenis_transaksi" class="block mt-1 w-full border-gray-300 rounded">
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                            <option value="penarikan_tunai">Penarikan Tunai</option>
                        </select>
                    </div>

                    <!-- Sumber Dana -->
                    <div class="mb-4">
                        <x-label for="sumber_dana" :value="__('Sumber Dana')" />
                        <x-input id="sumber_dana" class="block mt-1 w-full" type="text" name="sumber_dana" required />
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="mb-4">
                        <x-label for="metode_pembayaran" :value="__('Metode Pembayaran')" />
                        <select name="metode_pembayaran" id="metode_pembayaran" class="block mt-1 w-full border-gray-300 rounded" required>
                            <option value="" disabled selected hidden>Pilih Pembayaran</option>
                            <option value="tunai">Tunai</option>
                            <option value="non_tunai">Non Tunai</option>
                        </select>
                    </div>

                    <!-- Deskripsi & Nominal -->
                    <div class="mb-4">
                        <x-label for="deskripsi" :value="__('Deskripsi')" />
                        <x-input id="deskripsi" class="block mt-1 w-full" type="text" name="deskripsi" required />
                    </div>

                    <div class="mb-4">
                        <x-label for="nominal" :value="__('Nominal')" />
                        <x-input id="nominal" class="block mt-1 w-full" type="number" name="nominal" required />
                    </div>

                    <!-- Uang Lembaran (jika tunai) -->
                    <div id="lembaran-section" class="mt-4 hidden">
                        <label class="block font-semibold mb-2">Rincian Uang Lembaran:</label>

                        <div class="grid grid-cols-2 gap-4">
                            @foreach ([100000, 50000, 20000, 10000, 5000, 2000, 1000] as $nilai)
                            <div>
                                <label class="block text-sm mb-1">Rp {{ number_format($nilai) }}</label>
                                <input type="number" name="lembar[{{ $nilai }}]" min="0" value="0"
                                    class="w-full border border-gray-300 rounded px-2 py-1" />
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <x-button class="mt-4">Simpan</x-button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const metode = document.getElementById('metode_pembayaran');
        const jenis = document.getElementById('jenis_transaksi');
        const section = document.getElementById('lembaran-section');

        function toggleLembaran() {
            const inputs = section.querySelectorAll('input');

            if (metode.value === 'tunai') {
                section.classList.remove('hidden');
                inputs.forEach(input => input.disabled = false);
            } else {
                section.classList.add('hidden');
                inputs.forEach(input => input.disabled = true);
            }
        }
        if (metode && jenis) {
            metode.addEventListener('change', toggleLembaran);
            jenis.addEventListener('change', toggleLembaran);
            toggleLembaran(); // initial check saat halaman dimuat
        }
    </script>

</x-app-layout>