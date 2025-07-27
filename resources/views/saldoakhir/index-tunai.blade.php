<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Saldo Akhir Tunai</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (Auth::user()->role === 'bendahara')
                <a href="{{ route('saldo-akhir.tunai.create') }}">
                    <x-button>Tambah Saldo Tunai</x-button>
                </a>
                @endif

                <div class="overflow-x-auto rounded-xl shadow mt-4">
                    @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                    @endif
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-700 text-white uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left">Periode Bulan</th>
                                <th class="px-4 py-3 text-left">Tanggal Awal</th> {{-- Tambah ini --}}
                                <th class="px-4 py-3 text-left">Tanggal Akhir</th> {{-- Tambah ini --}}
                                <th class="px-4 py-3 text-left">Saldo Tunai</th>
                                <th class="px-4 py-3 text-left">Rincian Uang Lembaran</th>
                                <th class="px-4 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($saldos as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $item->periode_bulan }}</td>
                                <td class="px-4 py-2">{{ $item->tanggal_awal ? \Carbon\Carbon::parse($item->tanggal_awal)->format('d M Y') : '-' }}</td> {{-- Tanggal Awal --}}
                                <td class="px-4 py-2">{{ $item->tanggal_akhir ? \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') : '-' }}</td> {{-- Tanggal Akhir --}}
                                <td class="px-4 py-2 font-medium text-green-600">Rp {{ number_format($item->saldo_tunai, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-xs text-gray-700">
                                    100rb: {{ $item->lembar_100000 }} |
                                    50rb: {{ $item->lembar_50000 }} |
                                    20rb: {{ $item->lembar_20000 }} |
                                    10rb: {{ $item->lembar_10000 }} |
                                    5rb: {{ $item->lembar_5000 }} |
                                    2rb: {{ $item->lembar_2000 }} |
                                    1rb: {{ $item->lembar_1000 }} |
                                    500: {{ $item->lembar_500 }} |
                                    200: {{ $item->lembar_200 }} |
                                    100: {{ $item->lembar_100 }}
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('saldo-akhir.showTunai', $item->id) }}"><x-button>Detail</x-button></a>
                                        @if (Auth::user()->role === 'bendahara')
                                        <form action="{{ route('saldo-akhir.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button>Hapus</x-danger-button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center px-4 py-4 text-gray-500">Belum ada data saldo tunai.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $saldos->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>