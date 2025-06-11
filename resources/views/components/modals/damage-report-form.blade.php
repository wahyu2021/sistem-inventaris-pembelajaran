@props(['form']) {{-- <-- TAMBAHKAN BARIS INI --}}

{{-- Komponen ini akan secara otomatis mengakses properti publik dari komponen induknya ($isFormModalOpen) --}}
<x-dialog-modal wire:model.live="isFormModalOpen">
    <x-slot name="title">
        {{ $form->reportId ? 'Edit Laporan Kerusakan' : 'Tambah Laporan Kerusakan Baru' }}
    </x-slot>

    <x-slot name="content">
        {{-- Sisa dari kode form tidak perlu diubah, karena sekarang $form sudah dikenali --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Kolom Kiri --}}
            <div class="space-y-4">
                {{-- Lokasi (Input Teks dengan Suggestion) --}}
                <div>
                    <x-label for="locationSearch" value="Lokasi" />
                    <div class="relative">
                        <x-input type="text" class="mt-1 block w-full"
                            wire:model.live.debounce.300ms="form.locationSearch" wire:keyup="form.searchLocations"
                            placeholder="Ketik nama lokasi..." />

                        @if (!empty($form->locationSearchResults))
                            <ul
                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg max-h-48 overflow-y-auto">
                                @foreach ($form->locationSearchResults as $loc)
                                    <li wire:key="loc-result-{{ $loc->id }}"
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

                {{-- Dilaporkan Oleh --}}
                <div>
                    <x-label for="reported_by_name" value="Dilaporkan Oleh" />
                    <x-input id="reported_by_name" type="text" class="mt-1 block w-full"
                        wire:model="form.reported_by_name" />
                    <x-input-error for="form.reported_by_name" class="mt-1" />
                </div>

                {{-- Tipe Kerusakan --}}
                <div>
                    <x-label for="severity" value="Tipe Kerusakan" />
                    <select wire:model="form.severity"
                        class="mt-1 block w-full form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @foreach (\App\Models\DamageReport::$allowedSeverities as $sev)
                            <option value="{{ $sev }}">{{ Str::title($sev) }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="form.severity" class="mt-1" />
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="space-y-4">
                {{-- Status Laporan --}}
                <div>
                    <x-label for="status" value="Status Laporan" />
                    <select wire:model="form.status"
                        class="mt-1 block w-full form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @foreach (['dilaporkan', 'diverifikasi', 'dalam_perbaikan', 'selesai_diperbaiki', 'dihapuskan'] as $stat)
                            <option value="{{ $stat }}">{{ Str::title(str_replace('_', ' ', $stat)) }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="form.status" class="mt-1" />
                </div>

                {{-- Foto Kerusakan --}}
                <div>
                    <x-label for="newImageDamage" value="Foto Kerusakan (Opsional)" />
                    <input type="file" wire:model="form.newImageDamage" id="newImageDamage"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                    <x-input-error for="form.newImageDamage" class="mt-1" />

                    <div wire:loading wire:target="form.newImageDamage" class="text-sm text-gray-500 mt-1">Uploading...
                    </div>

                    @if ($form->newImageDamage)
                        <p class="mt-2 text-sm text-gray-600">Preview:</p>
                        <img src="{{ $form->newImageDamage->temporaryUrl() }}" alt="Preview"
                            class="mt-1 h-32 w-auto object-cover rounded shadow-sm">
                    @elseif ($form->image_damage)
                        <p class="text-sm text-gray-600 mt-2">Gambar Tersimpan:</p>
                        <img src="{{ Illuminate\Support\Facades\Storage::url($form->image_damage) }}"
                            alt="Gambar Tersimpan" class="mt-1 h-32 w-auto object-cover rounded shadow-sm">
                    @endif
                </div>
            </div>
        </div>

        {{-- Deskripsi Kerusakan --}}
        <div class="mt-6">
            <x-label for="description" value="Deskripsi Kerusakan" />
            <textarea wire:model="form.description" id="description" rows="4"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
            <x-input-error for="form.description" class="mt-1" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="$set('isFormModalOpen', false)" wire:loading.attr="disabled">
            Batal
        </x-secondary-button>

        <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
            Simpan
        </x-button>
    </x-slot>
</x-dialog-modal>
