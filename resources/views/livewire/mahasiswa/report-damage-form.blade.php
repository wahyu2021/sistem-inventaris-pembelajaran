<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ $headerTitle }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                <form wire:submit="submit">
                    <div class="space-y-6">

                        {{-- Jika lokasi sudah ditentukan, tampilkan sebagai info --}}
                        @if ($location)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Lokasi Dilaporkan:</h3>
                                <div class="p-4 border border-gray-200 rounded-md bg-gray-50 shadow-sm">
                                    <p class="font-semibold text-gray-800">{{ $location->name }}</p>
                                    <p class="text-gray-600">{{ $location->description }}</p>
                                </div>
                            </div>
                        @else
                            {{-- Jika lokasi BELUM ditentukan, tampilkan input pencarian --}}
                            <div>
                                <x-label for="locationSearch" value="Pilih Lokasi Kerusakan" />
                                <div class="relative">
                                    <x-input type="text" class="w-full mt-1"
                                        wire:model.live.debounce.300ms="form.locationSearch"
                                        wire:keyup="form.searchLocations" placeholder="Ketik nama lokasi..."
                                        autocomplete="off" />

                                    @if (!empty($form->locationSearchResults))
                                        <ul
                                            class="absolute z-20 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg max-h-48 overflow-y-auto">
                                            @foreach ($form->locationSearchResults as $loc)
                                                <li wire:key="loc-search-{{ $loc->id }}"
                                                    wire:click="form.selectLocation({{ $loc->id }}, '{{ addslashes($loc->name) }}')"
                                                    class="px-3 py-2 cursor-pointer hover:bg-blue-50 text-gray-700">
                                                    {{ $loc->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <x-input-error for="form.selectedLocationId" class="mt-1" />
                            </div>
                        @endif

                        {{-- Tingkat Kerusakan --}}
                        <div>
                            <x-label for="severity" value="Tingkat Kerusakan" />
                            <select wire:model="form.severity" class="form-select w-full mt-1 rounded-md shadow-sm">
                                @foreach (\App\Models\DamageReport::$allowedSeverities as $sev)
                                    <option value="{{ $sev }}">{{ Str::title($sev) }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="form.severity" class="mt-1" />
                        </div>

                        {{-- Deskripsi Kerusakan --}}
                        <div>
                            <x-label for="description" value="Deskripsikan Kerusakan" />
                            <textarea wire:model="form.description" rows="5" class="form-textarea w-full mt-1 rounded-md shadow-sm"></textarea>
                            <x-input-error for="form.description" class="mt-1" />
                        </div>

                        {{-- Unggah Foto --}}
                        <div>
                            <x-label for="newImageDamage" value="Unggah Foto Kerusakan (Opsional)" />
                            <input type="file" wire:model="form.newImageDamage"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200" />
                            <div wire:loading wire:target="form.newImageDamage" class="text-sm text-gray-500 mt-2">
                                Mengunggah...</div>
                            <x-input-error for="form.newImageDamage" class="mt-1" />

                            @if ($form->newImageDamage)
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600">Preview:</p>
                                    <img src="{{ $form->newImageDamage->temporaryUrl() }}" alt="Preview Foto Kerusakan"
                                        class="mt-2 h-40 w-auto object-cover rounded-lg border border-gray-300 shadow-sm">
                                </div>
                            @endif
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end pt-6 border-t border-gray-200 mt-6">
                            <a href="{{ route('mahasiswa.locations.index') }}"
                                class="mr-3 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Batal</a>
                            <x-button type="submit" wire:loading.attr="disabled">
                                Kirim Laporan
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
