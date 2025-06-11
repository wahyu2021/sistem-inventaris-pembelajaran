@props(['route', 'icon', 'title', 'color' => 'blue'])

@php
    $colorClasses = [
        'blue' => 'hover:bg-blue-100 bg-blue-100 text-blue-700',
        'amber' => 'hover:bg-amber-100 bg-amber-100 text-amber-600',
        'indigo' => 'hover:bg-indigo-100 bg-indigo-100 text-indigo-700',
    ];
    $selectedColor = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<a href="{{ $route }}"
    class="p-4 bg-slate-50 {{ explode(' ', $selectedColor)[0] }} rounded-lg transition duration-300 ease-in-out flex flex-col items-center justify-center space-y-2">
    <div class="p-3 rounded-full {{ explode(' ', $selectedColor)[1] }} {{ explode(' ', $selectedColor)[2] }}">
        <x-dynamic-component :component="$icon" class="w-7 h-7" />
    </div>
    <span class="text-sm font-medium text-slate-700">{{ $title }}</span>
</a>