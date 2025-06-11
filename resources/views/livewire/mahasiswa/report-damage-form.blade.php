<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{-- Menggunakan $headerTitle dari komponen --}}
            {{ $headerTitle }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">
                {{-- Menampilkan pesan flash setelah redirect --}}
                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-6 shadow-md"
                        role="alert">
                        <span class="block sm:inline font-semibold">{{ session('message') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="submitReport">
                    <div class="space-y-6">

                        {{-- BAGIAN LOKASI (Kondisional) --}}
                        @if ($locationId && $location)
                            {{-- Jika lokasi sudah ditentukan dari awal (via mount dari URL) --}}
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Lokasi Dilaporkan:</h3>
                                <div class="p-4 border border-gray-200 rounded-md bg-gray-50 shadow-sm">
                                    <p class="text-sm text-gray-800">
                                        <span class="font-semibold">Nama Lokasi:</span> {{ $location->name }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="font-semibold">Deskripsi:</span> {{ $location->description }}
                                    </p>
                                </div>
                            </div>
                        @else
                            {{-- Jika lokasi BELUM ditentukan, tampilkan input pencarian --}}
                            <div>
                                <label for="locationSearch" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pilih Lokasi Kerusakan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" wire:model.live.debounce.300ms="locationSearch"
                                        id="locationSearch"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 @error('selectedLocationId') border-red-500 @enderror"
                                        placeholder="Ketik nama lokasi..." autocomplete="off">

                                    {{-- Daftar Saran Hasil Pencarian --}}
                                    @if (strlen($locationSearch) >= 2 && count($locationSearchResults) > 0 && !$selectedLocationId)
                                        <ul
                                            class="absolute z-20 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg max-h-48 overflow-y-auto">
                                            @foreach ($locationSearchResults as $loc)
                                                <li wire:key="loc-search-{{ $loc->id }}"
                                                    wire:click="selectLocationFromSearch({{ $loc->id }}, '{{ addslashes($loc->name) }}')"
                                                    class="px-3 py-2 cursor-pointer hover:bg-blue-50 text-gray-700">
                                                    {{ $loc->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @elseif (strlen($locationSearch) >= 2 && count($locationSearchResults) == 0 && !$selectedLocationId)
                                        <div
                                            class="absolute z-20 w-full px-3 py-2 text-sm text-gray-500 mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                                            Tidak ada lokasi yang cocok.
                                        </div>
                                    @endif
                                </div>
                                @error('selectedLocationId')
                                    <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                                @enderror
                                @if ($selectedLocationId && $selectedLocationName && !$locationId)
                                    <p class="text-xs text-gray-600 mt-1">Lokasi Terpilih: <span
                                            class="font-semibold">{{ $selectedLocationName }}</span></p>
                                @endif
                            </div>
                        @endif
                        {{-- AKHIR BAGIAN LOKASI --}}

                        {{-- Tingkat Kerusakan --}}
                        <div>
                            <label for="severity" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kerusakan
                                <span class="text-red-500">*</span></label>
                            <select wire:model.defer="severity" id="severity"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 @error('severity') border-red-500 @enderror">
                                @foreach ($allowedSeverities as $sev)
                                    <option value="{{ $sev }}">{{ Str::title($sev) }}</option>
                                @endforeach
                            </select>
                            @error('severity')
                                <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Deskripsi Kerusakan --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsikan
                                Kerusakan <span class="text-red-500">*</span></label>
                            <textarea wire:model.defer="description" id="description" rows="5"
                                class="block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 @error('description') border-red-500 @enderror"
                                placeholder="Jelaskan detail kerusakan pada lokasi ini..."></textarea>
                            @error('description')
                                <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Unggah Foto Kerusakan --}}
                        <div>
                            <label for="newImageDamage" class="block text-sm font-medium text-gray-700 mb-1">Unggah Foto
                                Kerusakan (Opsional)</label>
                            <input type="file" wire:model="newImageDamage" id="newImageDamage"
                                class="block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-md file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-blue-100 file:text-blue-700
                                              hover:file:bg-blue-200 @error('newImageDamage') border-red-500 @enderror">
                            <div wire:loading wire:target="newImageDamage" class="text-sm text-gray-500 mt-2">
                                Mengunggah...</div>
                            @error('newImageDamage')
                                <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                            @enderror

                            @if ($newImageDamage && is_object($newImageDamage) && method_exists($newImageDamage, 'temporaryUrl'))
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600">Preview:</p>
                                    <img src="{{ $newImageDamage->temporaryUrl() }}" alt="Preview Foto Kerusakan"
                                        class="mt-2 h-40 w-auto object-cover rounded-lg border border-gray-300 shadow-sm">
                                </div>
                            @endif
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end pt-6 border-t border-gray-200 mt-6">
                            <a href="{{ route('mahasiswa.locations.index') }}"
                                class="inline-flex items-center px-6 py-2 mr-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-8 py-2 bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-50 transition ease-in-out duration-150"
                                wire:loading.attr="disabled" wire:target="submitReport, newImageDamage">
                                <span wire:loading.remove wire:target="submitReport">Kirim Laporan</span>
                                <span wire:loading wire:target="submitReport">Mengirim...
                                    <svg class="animate-spin -mr-1 ml-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
