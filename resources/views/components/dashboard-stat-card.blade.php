@props(['icon', 'title', 'value', 'color'])

@php
    $baseColorClasses = [
        'blue' => 'border-blue-700 text-blue-700 bg-blue-100',
        'amber' => 'border-amber-500 text-amber-600 bg-amber-100',
        'green' => 'border-green-500 text-green-600 bg-green-100',
    ];
    $colorClass = $baseColorClasses[$color] ?? 'border-gray-500 text-gray-600 bg-gray-100';
@endphp

<div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
    <div class="p-6 border-l-4 {{ explode(' ', $colorClass)[0] }}">
        <div class="flex items-center">
            <div class="p-3 rounded-full {{ explode(' ', $colorClass)[2] }} {{ explode(' ', $colorClass)[1] }}">
                <x-dynamic-component :component="$icon" class="w-6 h-6" />
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase tracking-wider">{{ $title }}</p>
                <p class="text-3xl font-bold {{ explode(' ', $colorClass)[1] }}">{{ $value }}</p>
            </div>
        </div>
    </div>
</div>