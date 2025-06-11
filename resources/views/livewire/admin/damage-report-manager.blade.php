<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Laporan Kerusakan') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                {{-- Judul dan Tombol Tambah --}}
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 sm:mb-0">Daftar Laporan Kerusakan</h3>
                    <button wire:click="openCreateModal()"
                        class="inline-flex items-center px-4 py-2 bg-blue-700 text-white text-sm font-semibold rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                        Tambah Laporan
                    </button>
                </div>

                {{-- Pesan Flash --}}
                @if (session('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-6 shadow-md"
                        role="alert">
                        <span class="block sm:inline font-semibold">{{ session('message') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-6 shadow-md"
                        role="alert">
                        <span class="block sm:inline font-semibold">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Filter dan Pencarian --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari..."
                        class="form-input rounded-md shadow-sm">
                    <select wire:model.live="filterLocation" class="form-select rounded-md shadow-sm">
                        <option value="">Semua Lokasi</option>
                        @foreach ($locationsForFilter as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterStatus" class="form-select rounded-md shadow-sm">
                        <option value="">Semua Status</option>
                        @foreach ($allowedStatuses as $stat)
                            <option value="{{ $stat }}">{{ Str::title(str_replace('_', ' ', $stat)) }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterSeverity" class="form-select rounded-md shadow-sm">
                        <option value="">Semua Tipe Kerusakan</option>
                        @foreach ($allowedSeverities as $sev)
                            <option value="{{ $sev }}">{{ Str::title($sev) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tabel Laporan Kerusakan --}}
                <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-200">
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
                                    Deskripsi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pelapor</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tgl Lapor</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($reports as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $report->location->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if ($report->severity == 'ringan') bg-green-100 text-green-800 @elseif($report->severity == 'sedang') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                            {{ Str::title($report->severity) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                        {{ $report->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $report->userReportedBy->name ?? $report->reported_by }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->reported_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if ($report->status == 'dilaporkan') bg-yellow-100 text-yellow-800 @elseif($report->status == 'diverifikasi') bg-blue-100 text-blue-800 @elseif($report->status == 'dalam_perbaikan') bg-indigo-100 text-indigo-800 @elseif($report->status == 'selesai_diperbaiki') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                            {{ Str::title(str_replace('_', ' ', $report->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button wire:click="openDetailModal({{ $report->id }})"
                                                class="text-green-600 hover:text-green-800">Detail</button>
                                            <button wire:click="openEditModal({{ $report->id }})"
                                                class="text-indigo-600 hover:text-indigo-800">Edit</button>
                                            <button wire:click="confirmReportDeletion({{ $report->id }})"
                                                title="Hapus" class="text-red-600 hover:text-red-800">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">Tidak ada laporan
                                        kerusakan ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($reports->hasPages())
                    <div class="mt-6">
                        {{ $reports->links('pagination::tailwind') }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- MEMANGGIL KOMPONEN BLADE MODAL --}}
    <x-modals.damage-report-form :form="$form" />
    <x-modals.damage-report-detail :report="$selectedReportDetail" />
    <x-confirmation-modal wire:model.live="confirmingReportDeletion">
        <x-slot name="title">
            Hapus Laporan Kerusakan
        </x-slot>

        <x-slot name="content">
            Apakah Anda yakin ingin menghapus laporan kerusakan ini? Tindakan ini tidak dapat dibatalkan.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingReportDeletion')" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteReport" wire:loading.attr="disabled">
                Hapus Laporan
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
