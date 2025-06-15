<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Daftar Laporan Kerusakan Saya') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                {{-- Header dan Tombol Aksi --}}
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 sm:mb-0">Laporan Kerusakan Anda</h3>
                    <a href="{{ route('mahasiswa.damages.report') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-700 text-white text-sm font-semibold rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                        Buat Laporan Baru
                    </a>
                </div>

                {{-- (Sisa dari filter dan pencarian tetap sama) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <x-input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari deskripsi, lokasi..." class="w-full" />
                    <select wire:model.live="filterLocation" class="form-select w-full rounded-md shadow-sm">
                        <option value="">Semua Lokasi</option>
                        @foreach ($allLocations as $location)
                            <option value="{{ $location->id }}">{{ Str::limit($location->name, 30) }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterStatus" class="form-select w-full rounded-md shadow-sm">
                        <option value="">Semua Status</option>
                        @foreach ($allowedStatuses as $stat)
                            <option value="{{ $stat }}">{{ Str::title(str_replace('_', ' ', $stat)) }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterSeverity" class="form-select w-full rounded-md shadow-sm">
                        <option value="">Semua Tipe</option>
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
                                    Deskripsi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Lapor</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($myReports as $report)
                                <tr class="hover:bg-gray-50 transition ease-in-out duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $report->location->name ?? 'Lokasi Dihapus' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 break-words max-w-xs">
                                        {{ Str::limit($report->description, 40) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->reported_at ? $report->reported_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <x-damage-status-badge :status="$report->status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <button wire:click="showReportDetail({{ $report->id }})"
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500"
                                            title="Lihat Detail">
                                            <x-heroicon-o-eye class="w-5 h-5" />
                                            <span class="sr-only">Lihat Detail</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <x-heroicon-o-clipboard-document-list class="h-12 w-12 text-gray-400" />
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Anda belum membuat
                                                laporan kerusakan.</h3>
                                            <p class="mt-1 text-sm text-gray-500">Silakan buat laporan baru untuk
                                                memulai.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi --}}
                @if ($myReports->hasPages())
                    <div class="mt-6">
                        {{ $myReports->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- =============================================== --}}
    {{--                   MODAL DETAIL                  --}}
    {{-- =============================================== --}}
    <x-dialog-modal wire:model.live="isReportDetailModalOpen">
        <x-slot name="title">
            Detail Laporan Kerusakan
        </x-slot>

        <x-slot name="content">
            @if ($selectedReportDetail)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="font-medium text-gray-700">ID Laporan:</p>
                        <p class="text-sm text-gray-800">{{ $selectedReportDetail->id }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700">Lokasi:</p>
                        <p class="text-sm text-gray-800">{{ $selectedReportDetail->location->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700">Dilaporkan Oleh:</p>
                        <p class="text-sm text-gray-800">
                            {{ $selectedReportDetail->userReportedBy->name ?? $selectedReportDetail->reported_by }}</p>
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
                        <x-damage-status-badge :status="$selectedReportDetail->status" />
                    </div>
                    @if ($selectedReportDetail->resolved_at)
                        <div class="col-span-2">
                            <p class="font-medium text-gray-700">Tanggal Diselesaikan:</p>
                            <p class="text-sm text-gray-800">
                                {{ $selectedReportDetail->resolved_at->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                    <div class="col-span-2">
                        <p class="font-medium text-gray-700">Deskripsi Kerusakan:</p>
                        <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $selectedReportDetail->description }}
                        </p>
                    </div>
                    @if ($selectedReportDetail->image_damage)
                        <div class="col-span-2">
                            <p class="font-medium text-gray-700">Foto Kerusakan:</p>
                            <img src="{{ Illuminate\Support\Facades\Storage::url($selectedReportDetail->image_damage) }}"
                                alt="Foto Kerusakan"
                                class="mt-1 max-h-80 w-auto object-contain rounded border shadow-sm">
                        </div>
                    @endif
                </div>
            @else
                <p>Memuat data laporan...</p>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeReportDetailModal" wire:loading.attr="disabled">
                Tutup
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>
