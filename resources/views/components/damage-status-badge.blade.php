@props(['status'])

@php
    $baseClasses = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full';
    $colorClasses = match ($status) {
        'dilaporkan' => 'bg-yellow-100 text-yellow-800',
        'diverifikasi' => 'bg-blue-100 text-blue-800',
        'dalam_perbaikan' => 'bg-indigo-100 text-indigo-800',
        'selesai_diperbaiki' => 'bg-green-100 text-green-800',
        'dihapuskan' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-800',
    };
@endphp

<span class="{{ $baseClasses }} {{ $colorClasses }}">
    {{ Str::title(str_replace('_', ' ', $status)) }}
</span>
