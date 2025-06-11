@props(['location'])

<div
    class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out border border-gray-200 flex flex-col overflow-hidden">
    {{-- Gambar Lokasi --}}
    @if ($location->image)
        <img src="{{ Illuminate\Support\Facades\Storage::url($location->image) }}" alt="{{ $location->name }}"
            class="h-48 w-full object-cover">
    @else
        {{-- Placeholder jika tidak ada gambar --}}
        <div class="h-48 w-full bg-gray-100 flex items-center justify-center text-gray-400 rounded-t-lg">
            <x-heroicon-o-photo class="h-16 w-16 text-gray-300" />
        </div>
    @endif

    <div class="p-4 flex flex-col flex-grow">
        {{-- Nama Lokasi --}}
        <h4 class="text-xl font-bold text-gray-900 mb-1 truncate" title="{{ $location->name }}">
            {{ $location->name }}
        </h4>

        {{-- Deskripsi --}}
        <p class="text-sm text-gray-600 mb-4 flex-grow">
            {{ Str::limit($location->description, 70, '...') }}
        </p>

        {{-- Tombol Aksi --}}
        <a href="{{ route('mahasiswa.damages.report', ['location' => $location->id]) }}"
            class="mt-auto block text-center w-full px-4 py-2 bg-blue-700 text-white text-sm font-semibold rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition ease-in-out duration-150 transform hover:scale-105">
            Laporkan Kerusakan di Sini
        </a>
    </div>
</div>
