<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Lokasi') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2 sm:mb-0">
                        Daftar Lokasi
                    </h3>
                    <button wire:click="openCreateModal()"
                        class="px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-opacity-50 transition ease-in-out duration-150">
                        <x-heroicon-o-plus class="h-5 w-5 inline-block -mt-1 mr-1" />
                        Tambah Data Lokasi
                    </button>
                </div>

                {{-- Flash Messages --}}
                @if (session('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Modal --}}
                <x-dialog-modal wire:model.live="isModalOpen">
                    <x-slot name="title">
                        {{ $form->locationId ? 'Edit Data Lokasi' : 'Tambah Data Lokasi Baru' }}
                    </x-slot>

                    <x-slot name="content">
                        <div class="space-y-4">
                            <div>
                                <x-label for="name" value="Nama Lokasi" />
                                <x-input id="name" type="text" class="mt-1 block w-full"
                                    wire:model="form.name" />
                                <x-input-error for="form.name" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="capacity" value="Kapasitas Ruangan (Opsional)" />
                                <x-input id="capacity" type="number" min="0" class="mt-1 block w-full"
                                    wire:model="form.capacity" />
                                <x-input-error for="form.capacity" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="description" value="Deskripsi (Item di ruangan)" />
                                <textarea wire:model="form.description" id="description" rows="3"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                <x-input-error for="form.description" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="newImage" value="Gambar Lokasi (Opsional)" />
                                <input type="file" wire:model="form.newImage" id="newImage"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                                <x-input-error for="form.newImage" class="mt-2" />

                                <div wire:loading wire:target="form.newImage" class="text-sm text-gray-500 mt-1">
                                    Uploading...</div>

                                @if ($form->newImage)
                                    <p class="mt-2 text-sm text-gray-600">Preview Gambar Baru:</p>
                                    <img src="{{ $form->newImage->temporaryUrl() }}" alt="Preview"
                                        class="mt-1 h-24 w-24 object-cover rounded">
                                @elseif ($form->image)
                                    <p class="mt-2 text-sm text-gray-600">Gambar Saat Ini:</p>
                                    <img src="{{ Storage::url($form->image) }}" alt="Gambar Saat Ini"
                                        class="mt-1 h-24 w-24 object-cover rounded">
                                @endif
                            </div>
                        </div>
                    </x-slot>

                    <x-slot name="footer">
                        <x-secondary-button wire:click="$set('isModalOpen', false)" wire:loading.attr="disabled">
                            Batal
                        </x-secondary-button>

                        <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                            Simpan
                        </x-button>
                    </x-slot>
                </x-dialog-modal>

                {{-- Filter dan Pencarian --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama lokasi atau deskripsi..."
                        class="col-span-1 md:col-span-3 form-input rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Tabel Lokasi --}}
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Gambar</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Lokasi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kapasitas</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deskripsi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($locations as $location)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($location->image)
                                            <img src="{{ Storage::url($location->image) }}" alt="{{ $location->name }}"
                                                class="h-12 w-12 rounded-md object-cover">
                                        @else
                                            <span
                                                class="h-12 w-12 rounded-md bg-gray-100 flex items-center justify-center text-xs text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $location->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $location->capacity ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 break-words max-w-xs">
                                        {{ Str::limit($location->description, 50) ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="openEditModal({{ $location->id }})"
                                            class="text-indigo-600 hover:text-indigo-800">Edit</button>
                                        <button wire:click="confirmLocationDeletion({{ $location->id }})"
                                            class="ml-3 text-red-600 hover:text-red-800">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada lokasi yang cocok dengan pencarian Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi --}}
                @if ($locations->hasPages())
                    <div class="mt-6">
                        {{ $locations->links('pagination::tailwind') }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <x-confirmation-modal wire:model.live="confirmingLocationDeletion">
        <x-slot name="title">
            Hapus Lokasi
        </x-slot>

        <x-slot name="content">
            Apakah Anda yakin ingin menghapus lokasi ini? Semua laporan kerusakan yang terkait dengan lokasi ini juga
            akan terhapus. Tindakan ini tidak dapat dibatalkan.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingLocationDeletion')" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteLocation" wire:loading.attr="disabled">
                Hapus Lokasi
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
