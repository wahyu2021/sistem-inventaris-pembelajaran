<div>
    {{-- Slot Header untuk x-app-layout --}}
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
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 sm:mb-0">
                        Daftar Laporan Kerusakan
                    </h3>
                    <button wire:click="create()"
                        class="inline-flex items-center px-4 py-2 bg-blue-700 text-white text-sm font-semibold rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                        Tambah Laporan Kerusakan
                    </button>
                </div>

                {{-- Pesan Flash --}}
                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-6 shadow-md"
                        role="alert">
                        <span class="block sm:inline font-semibold">{{ session('message') }}</span>
                    </div>
                @endif

                {{-- Modal Tambah/Edit --}}
                @if ($isOpen)
                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal()" aria-hidden="true"></div>
                        <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-3xl sm:w-full">
                            <form wire:submit.prevent="store">
                                <div class="bg-blue-700 px-4 py-4 sm:px-6">
                                    <h3 class="text-lg leading-6 font-bold text-white" id="modal-title">
                                        {{ $reportId ? 'Edit Laporan Kerusakan' : 'Tambah Laporan Kerusakan Baru' }}
                                    </h3>
                                </div>
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {{-- Kolom Kiri --}}
                                        <div class="space-y-4">
                                            {{-- Lokasi (Input Teks dengan Suggestion) --}}
                                            <div>
                                                <label for="locationSearch" class="block text-sm font-medium text-gray-700">Lokasi <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <input type="text" wire:model.live.debounce.300ms="locationSearch" id="locationSearch"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 @error('selectedLocationId') border-red-500 @enderror"
                                                        placeholder="Ketik nama lokasi...">

                                                    @if ($locationSearchResults && count($locationSearchResults) > 0 && strlen($locationSearch) >= 2 && !$selectedLocationId)
                                                        <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg max-h-48 overflow-y-auto">
                                                            @foreach ($locationSearchResults as $loc)
                                                                <li wire:key="loc-result-{{ $loc->id }}"
                                                                    wire:click="selectLocationFromModalSearch({{ $loc->id }}, '{{ $loc->name }}')"
                                                                    class="px-3 py-2 cursor-pointer hover:bg-blue-50 text-gray-700">
                                                                    {{ $loc->name }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @elseif (strlen($locationSearch) >= 2 && count($locationSearchResults) == 0 && !$selectedLocationId)
                                                        <div class="px-3 py-2 text-sm text-gray-500 mt-1">Tidak ada lokasi yang cocok.</div>
                                                    @endif

                                                    {{-- Input tersembunyi untuk menyimpan ID lokasi yang dipilih --}}
                                                    <input type="hidden" wire:model="selectedLocationId">
                                                </div>
                                                @error('selectedLocationId') <span class="text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
                                                @if ($selectedLocationId && $selectedLocationName)
                                                    <p class="text-xs text-gray-600 mt-1">Lokasi Terpilih: <span class="font-semibold">{{ $selectedLocationName }}</span></p>
                                                @endif
                                            </div>

                                            {{-- Dilaporkan Oleh (Input Manual) --}}
                                            <div>
                                                <label for="reported_by_name" class="block text-sm font-medium text-gray-700">Dilaporkan Oleh <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model.defer="reported_by_name" id="reported_by_name"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 @error('reported_by_name') border-red-500 @enderror"
                                                    placeholder="Nama pelapor">
                                                @error('reported_by_name') <span class="text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>

                                            {{-- TIPE KERUSAKAN --}}
                                            <div>
                                                <label for="severity" class="block text-sm font-medium text-gray-700">Tipe Kerusakan <span class="text-red-500">*</span></label>
                                                <select wire:model.defer="severity" id="severity"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 @error('severity') border-red-500 @enderror">
                                                    @foreach ($allowedSeverities as $sev)
                                                        <option value="{{ $sev }}">{{ Str::title($sev) }}</option>
                                                    @endforeach
                                                </select>
                                                @error('severity') <span class="text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        {{-- Kolom Kanan --}}
                                        <div class="space-y-4">
                                            {{-- Status Laporan --}}
                                            <div>
                                                <label for="status" class="block text-sm font-medium text-gray-700">Status Laporan <span class="text-red-500">*</span></label>
                                                <select wire:model.defer="status" id="status"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 @error('status') border-red-500 @enderror">
                                                    @foreach ($allowedStatuses as $stat)
                                                        <option value="{{ $stat }}">{{ Str::title(str_replace('_', ' ', $stat)) }}</option>
                                                    @endforeach
                                                </select>
                                                @error('status') <span class="text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>

                                            {{-- Foto Kerusakan --}}
                                            <div>
                                                <label for="newImageDamage" class="block text-sm font-medium text-gray-700">Foto Kerusakan (Opsional)</label>
                                                <input type="file" wire:model="newImageDamage" id="newImageDamage"
                                                    class="mt-1 block w-full text-sm text-gray-500
                                                    file:mr-4 file:py-2 file:px-4
                                                    file:rounded-md file:border-0
                                                    file:text-sm file:font-semibold
                                                    file:bg-blue-100 file:text-blue-700
                                                    hover:file:bg-blue-200 @error('newImageDamage') border-red-500 @enderror">
                                                @error('newImageDamage') <span class="text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
                                                <div wire:loading wire:target="newImageDamage" class="text-sm text-gray-500 mt-2">Mengunggah...</div>
                                                @if ($newImageDamage)
                                                    <p class="text-sm text-gray-600 mt-2">Preview:</p>
                                                    <img src="{{ $newImageDamage->temporaryUrl() }}" alt="Preview" class="mt-1 h-32 w-auto object-cover rounded shadow-sm">
                                                @elseif ($image_damage)
                                                    <p class="text-sm text-gray-600 mt-2">Gambar Tersimpan:</p>
                                                    <img src="{{ Storage::url($image_damage) }}" alt="Gambar Tersimpan" class="mt-1 h-32 w-auto object-cover rounded shadow-sm">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Deskripsi Kerusakan di bawah grid --}}
                                    <div class="mt-6">
                                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Kerusakan <span class="text-red-500">*</span></label>
                                        <textarea wire:model.defer="description" id="description" rows="4"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 @error('description') border-red-500 @enderror"
                                            placeholder="Jelaskan detail kerusakan pada laporan ini..."></textarea>
                                        @error('description') <span class="text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                {{-- Tombol Simpan/Batal Modal --}}
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-700 text-base font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                                        wire:loading.attr="disabled" wire:target="store, newImageDamage">
                                        <span wire:loading.remove wire:target="store">Simpan</span>
                                        <span wire:loading wire:target="store">Menyimpan...</span>
                                        <svg wire:loading wire:target="store" class="animate-spin -mr-1 ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="closeModal()"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- === MODAL UNTUK DETAIL LAPORAN KERUSAKAN (READ-ONLY) === --}}
                @if ($isReportDetailModalOpen && $selectedReportDetail)
                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
                        aria-labelledby="detail-report-modal-title" role="dialog" aria-modal="true">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                            wire:click="closeReportDetailModal()" aria-hidden="true"></div>
                        <div
                            class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-xl sm:w-full">
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

                {{-- Filter dan Pencarian --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    {{-- Pencarian --}}
                    <div>
                        <label for="search" class="sr-only">Cari</label>
                        <input type="text" wire:model.live.debounce.300ms="search" id="search"
                            placeholder="Cari deskripsi, lokasi, pelapor..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                    </div>
                    {{-- Filter Lokasi --}}
                    <div>
                        <label for="filterLocation" class="sr-only">Filter Lokasi</label>
                        <select wire:model.live="filterLocation" id="filterLocation"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                            <option value="">Semua Lokasi</option>
                            @foreach ($locationsForForm as $location)
                                {{-- Menggunakan locationsForForm --}}
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
                                {{-- Menggunakan allowedStatuses --}}
                                <option value="{{ $stat }}">
                                    {{ Str::title(str_replace('_', ' ', $stat)) }}</option>
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
                                {{-- Menggunakan allowedSeverities --}}
                                <option value="{{ $sev }}">{{ Str::title($sev) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tabel Laporan Kerusakan --}}
                <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-200">
                    {{-- Tambah border dan shadow --}}
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
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
                                    Pelapor</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tgl Lapor</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($reports as $report)
                                <tr
                                    class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100 transition ease-in-out duration-150">
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
                                        {{ Str::limit($report->description, 40) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $report->userReportedBy->name ?? ($report->reported_by ?? 'N/A') }}
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
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button wire:click="showReportDetail({{ $report->id }})"
                                                class="text-blue-600 hover:text-blue-800 p-1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition ease-in-out duration-150"
                                                title="Lihat Detail">
                                                <x-heroicon-o-eye class="w-5 h-5" />
                                                <span class="sr-only">Lihat Detail</span>
                                            </button>
                                            <button wire:click="edit({{ $report->id }})"
                                                class="text-indigo-600 hover:text-indigo-800 p-1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500 transition ease-in-out duration-150"
                                                title="Edit Laporan">
                                                <x-heroicon-o-pencil-square class="w-5 h-5" />
                                                <span class="sr-only">Edit Laporan</span>
                                            </button>
                                            <button wire:click="delete({{ $report->id }})"
                                                wire:confirm="Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan."
                                                class="text-red-600 hover:text-red-800 p-1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-500 transition ease-in-out duration-150"
                                                title="Hapus Laporan">
                                                <x-heroicon-o-trash class="w-5 h-5" />
                                                <span class="sr-only">Hapus Laporan</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"
                                        class="px-6 py-8 text-center text-gray-500 bg-gray-50 rounded-b-lg shadow-inner">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path vector-effect="non-scaling-stroke" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada laporan kerusakan
                                            ditemukan.</h3>
                                        <p class="mt-1 text-sm text-gray-500">Coba ubah filter atau kata kunci
                                            pencarian Anda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Link Paginasi --}}
                @if ($reports->hasPages())
                    <div class="mt-6 p-4 bg-white rounded-lg shadow-md flex justify-center">
                        {{ $reports->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>