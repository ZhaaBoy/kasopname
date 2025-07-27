<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Saldo Akhir Non Tunai</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <a href="{{ route('saldo-akhir.nontunai.create') }}">
                    <x-button>Tambah Saldo Non Tunai</x-button>
                </a>

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
                                <th class="px-4 py-3 text-left">Tanggal Awal</th>
                                <th class="px-4 py-3 text-left">Tanggal Akhir</th>
                                <th class="px-4 py-3 text-left">Saldo Non Tunai</th>
                                <th class="px-4 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($saldos as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $item->periode_bulan }}</td>
                                <td class="px-4 py-2">{{ $item->tanggal_awal ? \Carbon\Carbon::parse($item->tanggal_awal)->format('d M Y') : '-' }}</td>
                                <td class="px-4 py-2">{{ $item->tanggal_akhir ? \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') : '-' }}</td>
                                <td class="px-4 py-2 font-medium text-blue-600">Rp {{ number_format($item->saldo_non_tunai, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('saldo-akhir.showNontunai', $item->id) }}"><x-button>Detail</x-button></a>
                                        <form action="{{ route('saldo-akhir.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button>Hapus</x-danger-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center px-4 py-4 text-gray-500">Belum ada data saldo non tunai.</td>
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