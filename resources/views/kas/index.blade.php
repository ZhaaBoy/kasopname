<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Transaksi Kas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (Auth::user()->role === 'bendahara')
                <a href="{{ route('kas.create') }}"><x-button>Tambah Transaksi</x-button> </a>
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
                                <th scope="col" class="px-4 py-3 text-left">Tanggal</th>
                                <th scope="col" class="px-4 py-3 text-left">Jenis</th>
                                <th scope="col" class="px-4 py-3 text-left">Sumber Dana</th>
                                <th scope="col" class="px-4 py-3 text-left">Nominal</th>
                                <th scope="col" class="px-4 py-3 text-left">Pembayaran</th>
                                <th scope="col" class="px-4 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($kas as $k)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($k->tanggal_transaksi)->translatedFormat('d F Y') }}</td>

                                <td class="px-4 py-2 capitalize">{{ str_replace('_', ' ', $k->jenis_transaksi) }}</td>
                                <td class="px-4 py-2">{{ $k->sumber_dana ?? '-' }}</td>
                                <td class="px-4 py-2 font-medium text-green-600">Rp {{ number_format($k->nominal, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">{{ ucwords(str_replace('_', ' ', $k->metode_pembayaran)) }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('kas.show', $k->id) }}"><x-button>Detail</x-button> </a>
                                        @if (Auth::user()->role === 'bendahara')
                                        <form action="{{ route('kas.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
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
                                <td colspan="6" class="text-center px-4 py-4 text-gray-500">Belum ada data transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $kas->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>