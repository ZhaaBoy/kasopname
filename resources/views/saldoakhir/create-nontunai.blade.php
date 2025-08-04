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
                @if (session('errors'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('errors') }}
                </div>
                @endif
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
                    <input type="text" id="saldo_non_tunai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                    <input type="hidden" name="saldo_non_tunai" id="saldo_non_tunai_raw" required>
                </div>

                <div class="flex justify-end">
                    <x-button>Simpan</x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    const tanggalAwalInput = document.getElementById('tanggal_awal');
    const tanggalAkhirInput = document.getElementById('tanggal_akhir');
    const periodeInput = document.getElementById('periode_bulan');
    const saldoInput = document.getElementById('saldo_non_tunai');
    const saldoRawInput = document.getElementById('saldo_non_tunai_raw');

    function formatRupiah(angka) {
        return 'Rp. ' + angka.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&.').replace('.', '.');
    }

    function updatePeriodeDanSaldo() {
        const awal = tanggalAwalInput.value;
        const akhir = tanggalAkhirInput.value;

        // Update periode otomatis
        const awalDate = new Date(awal);
        const akhirDate = new Date(akhir);
        if (!isNaN(awalDate.getTime()) && !isNaN(akhirDate.getTime())) {
            const options = {
                month: 'long',
                year: 'numeric'
            };
            const bulanAwal = awalDate.toLocaleDateString('id-ID', options);
            const bulanAkhir = akhirDate.toLocaleDateString('id-ID', options);
            periodeInput.value = bulanAwal === bulanAkhir ? bulanAwal : `${bulanAwal} - ${bulanAkhir}`;
        }

        // Ambil saldo non tunai dari API
        if (awal && akhir) {
            fetch(`/api/saldo-non-tunai?tanggal_awal=${awal}&tanggal_akhir=${akhir}`)
                .then(response => response.json())
                .then(data => {
                    if (data.saldo_non_tunai !== undefined) {
                        const raw = Number(data.saldo_non_tunai);
                        saldoInput.value = formatRupiah(raw);
                        saldoRawInput.value = raw;
                    } else {
                        saldoInput.value = '';
                        saldoRawInput.value = '';
                    }
                }).catch(err => {
                    console.error('Gagal ambil saldo:', err);
                    saldoInput.value = '';
                    saldoRawInput.value = '';
                });
        }
    }

    tanggalAwalInput.addEventListener('change', updatePeriodeDanSaldo);
    tanggalAkhirInput.addEventListener('change', updatePeriodeDanSaldo);
</script>