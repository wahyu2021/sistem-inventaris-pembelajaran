<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Daftar Lokasi & Inventaris') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 sm:mb-0">
                        Cari & Pilih Lokasi
                    </h3>
                </div>

                {{-- Filter dan Pencarian --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="relative md:col-span-3">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama lokasi..."
                            class="w-full" />

                        @if (strlen($search) >= 2 && $searchResults->isNotEmpty())
                            <ul
                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg max-h-60 overflow-y-auto">
                                @foreach ($searchResults as $result)
                                    <li wire:key="search-result-{{ $result->id }}"
                                        wire:click="selectLocationFromSearch({{ $result->id }}, '{{ addslashes($result->name) }}')"
                                        class="px-3 py-2 cursor-pointer hover:bg-blue-50 text-gray-700">
                                        {{ $result->name }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div>
                        <select wire:model.live="quickFilter" class="form-select w-full rounded-md shadow-sm">
                            <option value="">Semua Tipe Lokasi</option>
                            <option value="lab">Hanya Lab</option>
                            <option value="ruang_teori">Hanya Ruang Teori</option>
                        </select>
                    </div>
                </div>

                {{-- Daftar Lokasi menggunakan komponen baru --}}
                @if ($locations->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($locations as $location)
                            <x-mahasiswa.location-card :location="$location" wire:key="location-{{ $location->id }}" />
                        @endforeach
                    </div>

                    @if ($locations->hasPages())
                        <div class="mt-10 p-4">
                            {{ $locations->links('pagination::tailwind') }}
                        </div>
                    @endif
                @else
                    <div
                        class="text-center py-16 bg-gray-50 rounded-lg shadow-inner border border-dashed border-gray-300">
                        <x-heroicon-o-magnifying-glass class="mx-auto h-16 w-16 text-gray-400" />
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak Ada Lokasi Ditemukan</h3>
                        <p class="mt-2 text-sm text-gray-600">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
