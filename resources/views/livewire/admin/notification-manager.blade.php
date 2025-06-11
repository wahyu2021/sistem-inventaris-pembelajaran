<div>
    {{-- Slot Header untuk x-app-layout --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifikasi Sistem') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2 sm:mb-0">
                        Daftar Pemberitahuan
                    </h3>
                    <div class="space-x-2">
                        <button wire:click="markAllAsRead"
                            wire:confirm="Anda yakin ingin menandai semua notifikasi sebagai sudah dibaca?"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50 transition ease-in-out duration-150">
                            Tandai Semua Sudah Dibaca
                        </button>
                        <button wire:click="confirmDeleteAll"
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-opacity-50 transition ease-in-out duration-150">
                            Hapus Semua Notifikasi
                        </button>
                    </div>
                </div>

                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                {{-- Filter dan Pencarian --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6"> {{-- Ubah menjadi 3 kolom --}}
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari isi notifikasi..."
                        class="form-input rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">

                    {{-- Filter Status Baca (Ditambahkan kembali) --}}
                    <select wire:model.live="filterReadStatus"
                        class="form-select rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status Baca</option>
                        <option value="read">Sudah Dibaca</option>
                        <option value="unread">Belum Dibaca</option>
                    </select>

                    {{-- Filter Berdasarkan Tingkat Kerusakan --}}
                    <select wire:model.live="filterSeverity"
                        class="form-select rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Tingkat Kerusakan</option>
                        @isset($allowedSeverities)
                            @foreach ($allowedSeverities as $severity)
                                <option value="{{ $severity }}">{{ Str::title($severity) }}</option>
                            @endforeach
                        @else
                            <option value="ringan">Ringan</option>
                            <option value="sedang">Sedang</option>
                            <option value="berat">Berat</option>
                        @endisset
                    </select>
                </div>


                {{-- Tabel Notifikasi (Konten tabel tetap sama) --}}
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
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($notifications as $notification)
                                <tr
                                    class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} {{ !$notification->read_at ? 'font-semibold text-gray-800' : 'text-gray-600' }} hover:bg-gray-100">
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            {{ $notification->data['message'] ?? ($notification->data['subject'] ?? ($notification->data['title'] ?? 'Notifikasi tidak dikenal')) }}
                                            @if (isset($notification->data['severity']))
                                                <span
                                                    class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if ($notification->data['severity'] == 'ringan') bg-green-100 text-green-800 
                                                    @elseif($notification->data['severity'] == 'sedang') bg-yellow-100 text-yellow-800 
                                                    @elseif($notification->data['severity'] == 'berat') bg-red-100 text-red-800 
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ Str::title($notification->data['severity']) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div
                                            class="text-xs {{ !$notification->read_at ? 'text-gray-600' : 'text-gray-500' }} mt-1">
                                            @if (isset($notification->data['location_name']))
                                                Lokasi: {{ $notification->data['location_name'] }}
                                            @endif
                                            @if (isset($notification->data['reporter_name']))
                                                | Pelapor: {{ $notification->data['reporter_name'] }}
                                            @endif
                                        </div>
                                        <button wire:click="showNotificationDetail('{{ $notification->id }}')"
                                            class="text-xs text-blue-700 hover:underline focus:outline-none mt-1">
                                            Lihat Detail
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"
                                        title="{{ $notification->created_at->format('d M Y H:i:s') }}">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($notification->read_at)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Sudah Dibaca
                                            </span>
                                            <span
                                                class="block text-xs text-gray-400 mt-1">{{ $notification->read_at->format('d M Y, H:i') }}</span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Belum Dibaca
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if (!$notification->read_at)
                                            <button wire:click="markAsRead('{{ $notification->id }}')"
                                                class="text-blue-700 hover:text-blue-900">Tandai Dibaca</button>
                                        @else
                                            <button wire:click="markAsUnread('{{ $notification->id }}')"
                                                class="text-gray-500 hover:text-gray-700">Tandai Belum</button>
                                        @endif
                                        <button wire:click="confirmDelete('{{ $notification->id }}')"
                                            class="ml-2 text-red-600 hover:text-red-800">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        Tidak ada pemberitahuan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($notifications->hasPages())
                    <div class="mt-6">
                        {{ $notifications->links('vendor.pagination.tailwind') }}
                        
                    </div>
                @endif

                {{-- Modal Detail (Tetap sama) --}}
                @if ($isDetailModalOpen && $selectedNotificationData)
                    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="detail-modal-title" role="dialog"
                        aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                wire:click="closeDetailModal()" aria-hidden="true"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                role="document">
                                <div class="bg-blue-700 px-4 py-3 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-white" id="detail-modal-title">
                                        Detail Notifikasi
                                    </h3>
                                </div>
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="space-y-3">
                                        @foreach ($selectedNotificationData as $key => $value)
                                            @if (is_string($value) || is_numeric($value))
                                                <div>
                                                    <strong
                                                        class="font-medium text-gray-700">{{ Str::title(str_replace('_', ' ', $key)) }}:</strong>
                                                    <p class="text-sm text-gray-600 break-words">
                                                        @if (
                                                            (Str::startsWith($value, 'http://') || Str::startsWith($value, 'https://')) &&
                                                                ($key === 'action_url' || Str::contains($key, '_url')))
                                                            <a href="{{ $value }}" target="_blank"
                                                                class="text-blue-700 hover:underline">{{ $value }}</a>
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </p>
                                                </div>
                                            @elseif (is_array($value))
                                                <div>
                                                    <strong
                                                        class="font-medium text-gray-700">{{ Str::title(str_replace('_', ' ', $key)) }}:</strong>
                                                    <pre class="text-xs text-gray-600 bg-gray-100 p-2 rounded overflow-x-auto">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="button" wire:click="closeDetailModal()"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-700 text-base font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                {{-- MODAL DELETE ALL --}}
                @if ($isDeleteAllModalOpen)
                    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                wire:click="cancelDeleteAll()" aria-hidden="true"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>

                            <div
                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        Konfirmasi Hapus Semua
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Apakah Anda yakin ingin menghapus <strong>semua notifikasi</strong>? Tindakan
                                        ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                                <div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button wire:click="deleteAllConfirmed"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700 sm:ml-3 sm:w-auto">
                                        Hapus Semua
                                    </button>
                                    <button wire:click="cancelDeleteAll"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- === MODAL HAPUS === --}}
                @if ($isDeleteModalOpen)
                    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                wire:click="cancelDelete()" aria-hidden="true"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>

                            <div
                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        Konfirmasi Hapus
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Apakah Anda yakin ingin menghapus notifikasi ini? Tindakan ini tidak dapat
                                        dibatalkan.
                                    </p>
                                </div>
                                <div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button wire:click="deleteConfirmed"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700 sm:ml-3 sm:w-auto">
                                        Hapus
                                    </button>
                                    <button wire:click="cancelDelete"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- === AKHIR MODAL HAPUS === --}}
            </div>
        </div>
    </div>
</div>
