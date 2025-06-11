@props(['report'])

{{-- Komponen ini akan secara otomatis mengakses properti publik dari induknya ($isDetailModalOpen) --}}
<x-dialog-modal wire:model.live="isDetailModalOpen">
    <x-slot name="title">
        Detail Laporan Kerusakan
    </x-slot>

    <x-slot name="content">
        @if ($report)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="font-medium text-gray-700">ID Laporan:</p>
                    <p class="text-sm text-gray-800">{{ $report->id }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Lokasi:</p>
                    <p class="text-sm text-gray-800">{{ $report->location->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Dilaporkan Oleh:</p>
                    <p class="text-sm text-gray-800">{{ $report->userReportedBy->name ?? $report->reported_by }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Tanggal Lapor:</p>
                    <p class="text-sm text-gray-800">
                        {{ $report->reported_at ? $report->reported_at->format('d M Y, H:i') : '-' }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Tipe Kerusakan:</p>
                    <p class="text-sm text-gray-800">{{ Str::title($report->severity) }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Status Laporan:</p>
                    <span
                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full @if ($report->status == 'dilaporkan') bg-yellow-100 text-yellow-800 @elseif($report->status == 'diverifikasi') bg-blue-100 text-blue-800 @elseif($report->status == 'dalam_perbaikan') bg-indigo-100 text-indigo-800 @elseif($report->status == 'selesai_diperbaiki') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        {{ Str::title(str_replace('_', ' ', $report->status)) }}
                    </span>
                </div>
                @if ($report->resolved_at)
                    <div class="col-span-2">
                        <p class="font-medium text-gray-700">Tanggal Diselesaikan:</p>
                        <p class="text-sm text-gray-800">{{ $report->resolved_at->format('d M Y, H:i') }}</p>
                    </div>
                @endif
                <div class="col-span-2">
                    <p class="font-medium text-gray-700">Deskripsi Kerusakan:</p>
                    <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $report->description }}</p>
                </div>
                @if ($report->image_damage)
                    <div class="col-span-2">
                        <p class="font-medium text-gray-700">Foto Kerusakan:</p>
                        <img src="{{ Illuminate\Support\Facades\Storage::url($report->image_damage) }}"
                            alt="Foto Kerusakan" class="mt-1 max-h-80 w-auto object-contain rounded border shadow-sm">
                    </div>
                @endif
            </div>
        @else
            <p>Memuat data laporan...</p>
        @endif
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="$set('isDetailModalOpen', false)" wire:loading.attr="disabled">
            Tutup
        </x-secondary-button>
    </x-slot>
</x-dialog-modal>
