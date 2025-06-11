<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Daftar Lokasi Inventaris') }} {{-- Judul diubah menjadi "Daftar Lokasi Inventaris" --}}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 sm:mb-0">
                        Cari & Pilih Lokasi {{-- Ubah dari "Barang" menjadi "Lokasi" --}}
                    </h3>
                    {{-- Tombol aksi lainnya (misal: tambah lokasi) bisa ditambahkan di sini --}}
                </div>

                {{-- Filter dan Pencarian --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8"> {{-- Dipertahankan 2 kolom untuk kesederhanaan, bisa disesuaikan lg:grid-cols-3 jika perlu --}}
                    {{-- Input Pencarian --}}
                    <div class="relative"> {{-- Penting: agar suggestion muncul di bawah input --}}
                        <label for="search" class="sr-only">Cari Lokasi</label>
                        <input type="text" wire:model.live.debounce.300ms="search" id="search"
                            placeholder="Cari nama lokasi..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">

                        @if ($searchResults && count($searchResults) > 0)
                            <ul
                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg max-h-60 overflow-y-auto">
                                @foreach ($searchResults as $result)
                                    <li wire:key="search-result-{{ $result->id }}"
                                        wire:click="selectLocationFromSearch({{ $result->id }}, '{{ $result->name }}')"
                                        class="px-3 py-2 cursor-pointer hover:bg-blue-50 text-gray-700">
                                        {{ $result->name }}
                                    </li>
                                @endforeach
                            </ul>
                        @elseif (strlen($search) >= 2 && count($searchResults) == 0 && $selectedLocationId == null)
                            {{-- Menampilkan pesan "Tidak ditemukan" jika tidak ada saran dan belum memilih --}}
                            <div class="px-3 py-2 text-sm text-gray-500 mt-1">Tidak ada lokasi yang cocok.</div>
                        @endif
                    </div>

                    {{-- Dropdown Filter Cepat --}}
                    <div>
                        <label for="quickFilter" class="sr-only">Filter Cepat</label>
                        <select wire:model.live="quickFilter" id="quickFilter"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                            <option value="">Semua Lokasi</option>
                            <option value="lab">Hanya Lab</option>
                            <option value="ruang_teori">Hanya Ruang Teori</option>
                        </select>
                    </div>
                </div>

                {{-- Daftar Lokasi (Bukan Item) --}}
                @if ($locations->count() > 0) {{-- Variabel 'locations' dari komponen Livewire --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($locations as $location)
                            {{-- Loop melalui 'locations' --}}
                            <div
                                class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out border border-gray-200 flex flex-col overflow-hidden">
                                {{-- Jika Lokasi memiliki gambar, tampilkan di sini. Jika tidak, bisa pakai placeholder default. --}}
                                @if ($location->image)
                                    {{-- Asumsi model Location memiliki kolom image_path --}}
                                    <img src="{{ Storage::url($location->image) }}" alt="{{ $location->name }}"
                                        class="h-48 w-full object-cover rounded-t-lg">
                                @else
                                    <div
                                        class="h-48 w-full bg-gray-100 flex items-center justify-center rounded-t-lg text-gray-400">
                                        <svg class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg> {{-- Icon lokasi Heroicons --}}
                                    </div>
                                @endif
                                <div class="p-4 flex flex-col flex-grow">
                                    <h4 class="text-xl font-extrabold text-gray-900 mb-1 truncate"
                                        title="{{ $location->name }}">{{ $location->name }}</h4>
                                    {{-- Jika ada deskripsi lokasi --}}
                                    <p class="text-sm text-gray-700 mb-3 flex-grow">
                                        {{ Str::limit($location->description, 70) ?? 'Tidak ada deskripsi.' }}</p>

                                    {{-- Informasi lain tentang lokasi bisa ditambahkan di sini, contoh: jumlah item di lokasi --}}
                                    {{-- <div class="text-xs text-gray-500 mb-1">Jumlah Item: <span class="font-semibold">{{ $location->items_count ?? 'N/A' }}</span></div> --}}
                                    {{-- Anda perlu menghitung items_count di Livewire jika ingin menampilkan ini --}}

                                    <a href="{{ route('mahasiswa.damages.report', ['location' => $location->id]) }}"
                                        {{-- Route ke halaman lapor kerusakan lokasi --}}
                                        class="mt-auto w-full text-center px-4 py-2 bg-blue-700 text-white text-base font-semibold rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition ease-in-out duration-150 transform hover:scale-105">
                                        Laporkan Kerusakan
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($locations->hasPages())
                        <div class="mt-10 p-4">
                            {{ $locations->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                @else
                    <div
                        class="text-center py-16 bg-gray-50 rounded-lg shadow-inner border border-dashed border-gray-300">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg> {{-- Mengganti ikon menjadi ikon lokasi --}}
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada lokasi ditemukan</h3>
                        <p class="mt-2 text-sm text-gray-600">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
