<div>
    {{-- Slot Header untuk x-app-layout --}}
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen"> {{-- Tambah bg-gray-100 dan min-h-screen --}}
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Selamat Datang --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-900"> {{-- Font lebih tebal --}}
                        Selamat Datang Kembali, {{ Auth::user()->name }}!
                    </h1>
                    <p class="mt-4 text-gray-700 leading-relaxed"> {{-- Warna teks lebih gelap --}}
                        Ini adalah ringkasan aktivitas dan informasi terkait penggunaan sistem inventaris pembelajaran.
                    </p>
                </div>
            </div>

            {{-- Kartu Statistik Laporan Anda --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 ease-in-out sm:rounded-lg">
                    <div class="p-6 border-l-4 border-blue-700"> {{-- Border kiri --}}
                        <p class="text-sm text-gray-500 uppercase tracking-wider">Total Laporan Anda</p>
                        <p class="text-3xl font-bold text-blue-700">{{ $totalMyReports }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 ease-in-out sm:rounded-lg">
                    <div class="p-6 border-l-4 border-yellow-500"> {{-- Border kiri --}}
                        <p class="text-sm text-gray-500 uppercase tracking-wider">Laporan Terbuka</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $myOpenReports }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 ease-in-out sm:rounded-lg">
                    <div class="p-6 border-l-4 border-green-500"> {{-- Border kiri --}}
                        <p class="text-sm text-gray-500 uppercase tracking-wider">Laporan Selesai</p>
                        <p class="text-3xl font-bold text-green-600">{{ $myResolvedReports }}</p>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi Cepat --}}
            <div class="mb-8 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('mahasiswa.locations.index') }}" {{-- Ubah route ke daftar lokasi --}}
                    class="w-full sm:w-auto justify-center inline-flex items-center px-6 py-3 bg-blue-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-800 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    <x-heroicon-o-map-pin class="h-5 w-5 mr-2" /> {{-- Ikon lokasi --}}
                    Lihat Semua Lokasi
                </a>
                <a href="{{ route('mahasiswa.damages.report') }}" {{-- Route ke form lapor kerusakan (lokasi) --}}
                    class="w-full sm:w-auto justify-center inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                    <x-heroicon-o-exclamation-triangle class="h-5 w-5 mr-2" /> {{-- Ikon peringatan/kerusakan --}}
                    Laporkan Kerusakan Baru
                </a>
            </div>

            {{-- Daftar Laporan Kerusakan Terbaru Anda --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <h3 class="text-xl font-bold text-blue-700 mb-4">Laporan Kerusakan Terbaru Anda</h3> {{-- Font lebih tebal --}}
                    @if ($recentDamageReports && $recentDamageReports->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Lokasi</th> {{-- Ubah dari Barang ke Lokasi --}}
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tipe Kerusakan</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Deskripsi Singkat</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pelapor</th> {{-- Tambah kolom pelapor --}}
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
                                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100 transition ease-in-out duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $report->location->name ?? 'Lokasi tidak tersedia' }} {{-- Akses nama lokasi --}}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if ($report->severity == 'ringan') bg-green-100 text-green-800
                                                    @elseif($report->severity == 'sedang') bg-yellow-100 text-yellow-800
                                                    @elseif($report->severity == 'berat') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ Str::title($report->severity) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 break-words max-w-xs"> {{-- max-w-xs untuk wrap teks --}}
                                                {{ Str::limit($report->description, 50) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $report->userReportedBy->name ?? $report->reported_by ?? 'N/A' }} {{-- Akses nama pelapor --}}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $report->reported_at ? $report->reported_at->format('d M Y, H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if ($report->status == 'dilaporkan') bg-yellow-100 text-yellow-800
                                                    @elseif($report->status == 'diverifikasi') bg-blue-100 text-blue-700
                                                    @elseif($report->status == 'dalam_perbaikan') bg-indigo-100 text-indigo-800
                                                    @elseif($report->status == 'selesai_diperbaiki') bg-green-100 text-green-800
                                                    @elseif($report->status == 'dihapuskan') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ Str::title(str_replace('_', ' ', $report->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($totalMyReports > $recentDamageReports->count())
                            <div class="mt-6 p-4 text-sm text-center bg-gray-50 rounded-b-lg border-t border-gray-200"> {{-- Styling untuk "Lihat semua laporan" --}}
                                <a href="{{ route('mahasiswa.damages.my') }}" class="text-blue-700 hover:text-blue-900 hover:underline font-semibold"> {{-- Sesuaikan route ini --}}
                                    Lihat semua laporan Anda ({{ $totalMyReports }}) &rarr;
                                </a>
                            </div>
                        @endif  
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg shadow-inner border border-dashed border-gray-300">
                            <x-heroicon-o-clipboard-document-list class="mx-auto h-12 w-12 text-gray-400" /> {{-- Ikon daftar laporan --}}
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Anda belum membuat laporan kerusakan.</h3>
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