<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Saldo Akhir Tunai
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('saldo-akhir.tunai.store') }}">
                @csrf
                @if (session('errors'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('errors') }}
                </div>
                @endif
                <div class="mb-4">
                    <label for="periode_bulan" class="block text-sm font-medium text-gray-700">Periode Bulan</label>
                    <input type="text" name="periode_bulan" id="periode_bulan"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly>
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
                    <label for="saldo_tunai" class="block text-sm font-medium text-gray-700">Saldo Tunai</label>
                    <input type="text" id="saldo_tunai_display"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                    <input type="hidden" name="saldo_tunai" id="saldo_tunai" required>
                </div>

                <h4 class="font-semibold mb-2">Rincian Uang Lembaran:</h4>

                @php
                $pecahan = [100000, 50000, 20000, 10000, 5000, 2000, 1000, 500, 200, 100];
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                    @foreach ($pecahan as $p)
                    <div>
                        <label class="block text-sm text-gray-600">Lembar {{ number_format($p, 0, ',', '.') }}</label>
                        <input type="number" name="lembar_{{ $p }}" class="w-full rounded-md border-gray-300 shadow-sm"
                            value="0" min="0">
                    </div>
                    @endforeach
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
    const saldoInput = document.getElementById('saldo_tunai');

    function formatRupiah(angka) {
        return 'Rp. ' + angka.toFixed(2)
            .replace(/\d(?=(\d{3})+\.)/g, '$&.')
            .replace(',', ',');
    }

    function updatePeriodeDanSaldo() {
        const awal = tanggalAwalInput.value;
        const akhir = tanggalAkhirInput.value;

        // Update periode
        const awalDate = new Date(awal);
        const akhirDate = new Date(akhir);
        if (!isNaN(awalDate.getTime()) && !isNaN(akhirDate.getTime())) {
            const options = {
                month: 'long',
                year: 'numeric'
            };
            const bulanAwal = awalDate.toLocaleDateString('id-ID', options);
            const bulanAkhir = akhirDate.toLocaleDateString('id-ID', options);

            periodeInput.value = (bulanAwal === bulanAkhir) ? bulanAwal : `${bulanAwal} - ${bulanAkhir}`;
        }

        // Fetch saldo tunai otomatis
        if (awal && akhir) {
            fetch(`/api/saldo-tunai?tanggal_awal=${awal}&tanggal_akhir=${akhir}`)
                .then(response => response.json())
                .then(data => {
                    if (data.saldo_tunai !== undefined) {
                        const raw = Number(data.saldo_tunai);
                        document.getElementById('saldo_tunai_display').value = formatRupiah(raw);
                        saldoInput.value = raw;
                    } else {
                        document.getElementById('saldo_tunai_display').value = '';
                        saldoInput.value = '';
                    }
                }).catch(err => {
                    console.error('Gagal mengambil saldo:', err);
                    saldoInput.value = '';
                });
        }
    }

    tanggalAwalInput.addEventListener('change', updatePeriodeDanSaldo);
    tanggalAkhirInput.addEventListener('change', updatePeriodeDanSaldo);
</script>