<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifikasi Sistem') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                {{-- Header dan Tombol Aksi --}}
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2 sm:mb-0">Daftar Pemberitahuan</h3>
                    <div class="space-x-2">
                        <button wire:click="markAllAsRead"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                            Tandai Semua Sudah Dibaca
                        </button>
                        <button wire:click="confirmDeleteAll"
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-opacity-50">
                            Hapus Semua Notifikasi
                        </button>
                    </div>
                </div>

                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                {{-- Filter dan Pencarian --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari isi notifikasi..."
                        class="form-input rounded-md shadow-sm">
                    <select wire:model.live="filterReadStatus" class="form-select rounded-md shadow-sm">
                        <option value="">Semua Status Baca</option>
                        <option value="read">Sudah Dibaca</option>
                        <option value="unread">Belum Dibaca</option>
                    </select>
                    <select wire:model.live="filterSeverity" class="form-select rounded-md shadow-sm">
                        <option value="">Semua Tingkat Kerusakan</option>
                        @foreach ($allowedSeverities as $severity)
                            <option value="{{ $severity }}">{{ Str::title($severity) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tabel Notifikasi --}}
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pesan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($notifications as $notification)
                                <tr class="{{ !$notification->read_at ? 'bg-blue-50' : 'bg-white' }} hover:bg-gray-100">
                                    <td class="px-6 py-4">
                                        <div
                                            class="text-sm {{ !$notification->read_at ? 'font-semibold text-gray-800' : 'text-gray-600' }}">
                                            {{ $notification->data['message'] ?? 'Notifikasi tidak dikenal' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <button wire:click="openDetailModal('{{ $notification->id }}')"
                                                class="text-blue-600 hover:underline">Lihat Detail</button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div title="{{ $notification->created_at->format('d M Y H:i:s') }}">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                        @if ($notification->read_at)
                                            <span class="text-xs text-green-600">(Dibaca)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if (!$notification->read_at)
                                            <button wire:click="markAsRead('{{ $notification->id }}')"
                                                class="text-blue-600 hover:text-blue-800">Tandai Dibaca</button>
                                        @else
                                            <button wire:click="markAsUnread('{{ $notification->id }}')"
                                                class="text-gray-500 hover:text-gray-700">Tandai Belum</button>
                                        @endif
                                        <button wire:click="confirmDelete('{{ $notification->id }}')"
                                            class="ml-3 text-red-600 hover:text-red-800">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                        Tidak ada pemberitahuan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($notifications->hasPages())
                    <div class="mt-6">{{ $notifications->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    <x-dialog-modal wire:model.live="isDetailModalOpen">
        <x-slot name="title">Detail Notifikasi</x-slot>
        <x-slot name="content">
            @if ($selectedNotificationData)
                <div class="space-y-3">
                    @foreach ($selectedNotificationData as $key => $value)
                        <div>
                            <strong
                                class="font-medium text-gray-700">{{ Str::title(str_replace('_', ' ', $key)) }}:</strong>
                            <p class="text-sm text-gray-600 break-words">
                                {{ is_array($value) ? json_encode($value) : $value }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isDetailModalOpen', false)">Tutup</x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    {{-- Modal Konfirmasi Hapus Satu Notifikasi --}}
    <x-confirmation-modal wire:model.live="confirmingNotificationDeletion">
        <x-slot name="title">Hapus Notifikasi</x-slot>
        <x-slot name="content">Apakah Anda yakin ingin menghapus notifikasi ini? Tindakan ini tidak dapat
            dibatalkan.</x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingNotificationDeletion', false)">Batal</x-secondary-button>
            <x-danger-button class="ms-3" wire:click="deleteSelectedNotification">Hapus</x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- Modal Konfirmasi Hapus Semua Notifikasi --}}
    <x-confirmation-modal wire:model.live="confirmingDeleteAll">
        <x-slot name="title">Hapus Semua Notifikasi</x-slot>
        <x-slot name="content">Apakah Anda yakin ingin menghapus SEMUA notifikasi? Tindakan ini tidak dapat
            dibatalkan.</x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingDeleteAll', false)">Batal</x-secondary-button>
            <x-danger-button class="ms-3" wire:click="deleteAllNotifications">Hapus Semua</x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
