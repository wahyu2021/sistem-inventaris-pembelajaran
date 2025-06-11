<div>
    {{-- Slot Header untuk x-app-layout --}}
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Daftar Laporan Kerusakan Saya') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                {{-- Judul Halaman --}}
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 sm:mb-0">
                        Laporan Kerusakan Anda
                    </h3>
                    <a href="{{ route('mahasiswa.damages.report') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-700 text-white text-sm font-semibold rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                        Buat Laporan Baru
                    </a>
                </div>

                {{-- Pesan Flash --}}
                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-6 shadow-md"
                        role="alert">
                        <span class="block sm:inline font-semibold">{{ session('message') }}</span>
                    </div>
                @endif

                {{-- Filter dan Pencarian --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    {{-- Pencarian --}}
                    <div>
                        <label for="search" class="sr-only">Cari</label>
                        <input type="text" wire:model.live.debounce.300ms="search" id="search"
                            placeholder="Cari deskripsi, lokasi..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                    </div>
                    {{-- Filter Lokasi --}}
                    <div>
                        <label for="filterLocation" class="sr-only">Filter Lokasi</label>
                        <select wire:model.live="filterLocation" id="filterLocation"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                            <option value="">Semua Lokasi</option>
                            @foreach ($allLocations as $location)
                                <option value="{{ $location->id }}">{{ Str::limit($location->name, 30) }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Filter Status --}}
                    <div>
                        <label for="filterStatus" class="sr-only">Filter Status</label>
                        <select wire:model.live="filterStatus" id="filterStatus"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                            <option value="">Semua Status Laporan</option>
                            @foreach ($allowedStatuses as $stat)
                                <option value="{{ $stat }}">{{ Str::title(str_replace('_', ' ', $stat)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Filter Tipe Kerusakan --}}
                    <div>
                        <label for="filterSeverity" class="sr-only">Filter Tipe Kerusakan</label>
                        <select wire:model.live="filterSeverity" id="filterSeverity"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                            <option value="">Semua Tipe Kerusakan</option>
                            @foreach ($allowedSeverities as $sev)
                                <option value="{{ $sev }}">{{ Str::title($sev) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tabel Laporan Kerusakan --}}
                <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Lokasi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipe Kerusakan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deskripsi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Lapor</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($myReports as $report)
                                <tr
                                    class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100 transition ease-in-out duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $report->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $report->location->name ?? 'Lokasi Dihapus' }}
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
                                    <td class="px-6 py-4 text-sm text-gray-600 break-words max-w-xs">
                                        {{ Str::limit($report->description, 40) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->reported_at ? $report->reported_at->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($report->status == 'dilaporkan') bg-yellow-100 text-yellow-800
                                            @elseif($report->status == 'diverifikasi') bg-blue-100 text-blue-800
                                            @elseif($report->status == 'dalam_perbaikan') bg-indigo-100 text-indigo-800
                                            @elseif($report->status == 'selesai_diperbaiki') bg-green-100 text-green-800
                                            @elseif($report->status == 'dihapuskan') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ Str::title(str_replace('_', ' ', $report->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                        <button wire:click="showReportDetail({{ $report->id }})"
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition ease-in-out duration-150"
                                            title="Lihat Detail">
                                            <x-heroicon-o-eye class="w-5 h-5" />
                                            <span class="sr-only">Lihat Detail</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"
                                        class="px-6 py-8 text-center text-gray-500 bg-gray-50 rounded-b-lg shadow-inner border-t border-gray-200">
                                        <x-heroicon-o-clipboard-document-list class="mx-auto h-12 w-12 text-gray-400" />
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Anda belum membuat laporan
                                            kerusakan.</h3>
                                        <p class="mt-1 text-sm text-gray-500">Silakan buat laporan baru untuk memulai.
                                        </p>
                                        <div class="mt-6">
                                            <a href="{{ route('mahasiswa.lapor-kerusakan') }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                                <x-heroicon-o-plus class="h-4 w-4 mr-2" />
                                                Buat Laporan Pertama Anda
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Link Paginasi --}}
                @if ($myReports->hasPages())
                    <div class="mt-6 p-4 bg-white rounded-lg shadow-md flex justify-center">
                        {{ $myReports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- === MODAL UNTUK DETAIL LAPORAN KERUSAKAN (READ-ONLY) === --}}
    @if ($isReportDetailModalOpen && $selectedReportDetail)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" aria-labelledby="detail-report-modal-title"
            role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                wire:click="closeReportDetailModal()" aria-hidden="true"></div>
            <div
                class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <div class="bg-blue-700 px-4 py-4 sm:px-6">
                    <h3 class="text-lg leading-6 font-bold text-white" id="detail-report-modal-title">
                        Detail Laporan Kerusakan
                    </h3>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="font-medium text-gray-700">ID Laporan:</p>
                            <p class="text-sm text-gray-800">{{ $selectedReportDetail->id }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Lokasi:</p>
                            <p class="text-sm text-gray-800">
                                {{ $selectedReportDetail->location->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Dilaporkan Oleh:</p>
                            <p class="text-sm text-gray-800">
                                {{ $selectedReportDetail->userReportedBy->name ?? ($selectedReportDetail->reported_by ?? 'N/A') }}
                            </p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Tanggal Lapor:</p>
                            <p class="text-sm text-gray-800">
                                {{ $selectedReportDetail->reported_at ? $selectedReportDetail->reported_at->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Tipe Kerusakan:</p>
                            <p class="text-sm text-gray-800">{{ Str::title($selectedReportDetail->severity) }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Status Laporan:</p>
                            <p class="text-sm text-gray-800">
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($selectedReportDetail->status == 'dilaporkan') bg-yellow-100 text-yellow-800
                                    @elseif($selectedReportDetail->status == 'diverifikasi') bg-blue-100 text-blue-800
                                    @elseif($selectedReportDetail->status == 'dalam_perbaikan') bg-indigo-100 text-indigo-800
                                    @elseif($selectedReportDetail->status == 'selesai_diperbaiki') bg-green-100 text-green-800
                                    @elseif($selectedReportDetail->status == 'dihapuskan') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ Str::title(str_replace('_', ' ', $selectedReportDetail->status)) }}
                                </span>
                            </p>
                        </div>
                        @if ($selectedReportDetail->resolved_at)
                            <div class="col-span-2">
                                <p class="font-medium text-gray-700">Tanggal Diselesaikan:</p>
                                <p class="text-sm text-gray-800">
                                    {{ $selectedReportDetail->resolved_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        @endif
                        <div class="col-span-2">
                            <p class="font-medium text-gray-700">Deskripsi Kerusakan:</p>
                            <p class="text-sm text-gray-800 whitespace-pre-wrap">
                                {{ $selectedReportDetail->description }}</p>
                        </div>
                        @if ($selectedReportDetail->image_damage)
                            <div class="col-span-2">
                                <p class="font-medium text-gray-700">Foto Kerusakan:</p>
                                <img src="{{ Storage::url($selectedReportDetail->image_damage) }}"
                                    alt="Foto Kerusakan"
                                    class="mt-1 max-h-80 w-auto object-contain rounded border shadow-sm">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 text-right">
                    <button type="button" wire:click="closeReportDetailModal()"
                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-700 text-base font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
    {{-- === AKHIR MODAL DETAIL LAPORAN === --}}
</div>
