@props(['title', 'items'])

<div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-slate-700 mb-4 border-b pb-2 border-slate-200">
            {{ $title }}
        </h3>
        @if ($items && $items->count() > 0)
            <ul class="divide-y divide-slate-200">
                {{-- Slot untuk menampung item-item list --}}
                {{ $slot }}
            </ul>
        @else
            <p class="text-sm text-gray-500">Tidak ada aktivitas baru.</p>
        @endif
    </div>
</div>