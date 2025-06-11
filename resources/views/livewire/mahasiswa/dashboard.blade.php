<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Selamat Datang --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8 p-6 lg:p-8 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">
                    Selamat Datang Kembali, {{ Auth::user()->name }}!
                </h1>
                <p class="mt-4 text-gray-700 leading-relaxed">
                    Ini adalah ringkasan aktivitas dan informasi terkait penggunaan sistem inventaris pembelajaran.
                </p>
            </div>

            {{-- Kartu Statistik (Menggunakan kembali komponen dari dasbor admin) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <x-dashboard-stat-card icon="heroicon-o-clipboard-document-list" title="Total Laporan Anda"
                    :value="$totalMyReports" color="blue" />
                <x-dashboard-stat-card icon="heroicon-o-exclamation-triangle" title="Laporan Terbuka" :value="$myOpenReports"
                    color="amber" />
                <x-dashboard-stat-card icon="heroicon-o-check-circle" title="Laporan Selesai" :value="$myResolvedReports"
                    color="green" />
            </div>

            {{-- Tombol Aksi Cepat --}}
            <div class="mb-8 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('mahasiswa.locations.index') }}"
                    class="w-full sm:w-auto justify-center inline-flex items-center px-6 py-3 bg-blue-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-800 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    <x-heroicon-o-map-pin class="h-5 w-5 mr-2" />
                    Lihat Semua Lokasi
                </a>
                <a href="{{ route('mahasiswa.damages.report') }}"
                    class="w-full sm:w-auto justify-center inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                    <x-heroicon-o-exclamation-triangle class="h-5 w-5 mr-2" />
                    Laporkan Kerusakan Baru
                </a>
            </div>

            {{-- Daftar Laporan Kerusakan Terbaru Anda --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <h3 class="text-xl font-bold text-blue-700 mb-4">Laporan Kerusakan Terbaru Anda</h3>
                    @if ($recentDamageReports->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Lokasi</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tipe</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Lapor</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($recentDamageReports as $report)
                                        <tr class="hover:bg-gray-50 transition ease-in-out duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                title="{{ $report->description }}">
                                                {{ $report->location->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if ($report->severity == 'ringan') bg-green-100 text-green-800 @elseif($report->severity == 'sedang') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                                    {{ Str::title($report->severity) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $report->reported_at ? $report->reported_at->format('d M Y, H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{-- Menggunakan komponen badge status baru --}}
                                                <x-damage-status-badge :status="$report->status" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($totalMyReports > $recentDamageReports->count())
                            <div class="mt-6 p-4 text-sm text-center bg-gray-50 rounded-b-lg border-t border-gray-200">
                                <a href="{{ route('mahasiswa.damages.my') }}"
                                    class="text-blue-700 hover:text-blue-900 hover:underline font-semibold">
                                    Lihat semua laporan Anda ({{ $totalMyReports }}) &rarr;
                                </a>
                            </div>
                        @endif
                    @else
                        {{-- Tampilan Empty State --}}
                        <div
                            class="text-center py-8 bg-gray-50 rounded-lg shadow-inner border border-dashed border-gray-300">
                            <x-heroicon-o-clipboard-document-list class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Anda belum membuat laporan kerusakan.
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Silakan buat laporan baru untuk memulai.</p>
                            <div class="mt-6">
                                <a href="{{ route('mahasiswa.damages.report') }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                    <x-heroicon-o-plus class="h-4 w-4 mr-2" />
                                    Buat Laporan Pertama Anda
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
