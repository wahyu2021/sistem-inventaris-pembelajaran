{{-- Slot Header untuk x-app-layout --}}
<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            {{-- Salam Pembuka dan Tanggal --}}
            <div class="mb-8 px-4 sm:px-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Selamat Datang Kembali,
                    {{ Auth::user()->name ?? 'Admin' }}!</h1>
                <p class="text-sm text-slate-600">Berikut adalah ringkasan aktivitas sistem Anda hari
                    ini, {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}.</p>
            </div>

            {{-- Kartu Statistik Utama --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Total Lokasi --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 border-l-4 border-blue-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-700">
                                <x-heroicon-o-building-office class="w-6 h-6" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 uppercase tracking-wider">Total
                                    Lokasi</p>
                                <p class="text-3xl font-bold text-blue-700">
                                    {{ $totalLocations ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Laporan Kerusakan Terbuka --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 border-l-4 border-amber-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-amber-100 text-amber-600">
                                <x-heroicon-o-exclamation-triangle class="w-6 h-6" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 uppercase tracking-wider">Laporan
                                    Rusak Terbuka</p>
                                <p class="text-3xl font-bold text-amber-600">
                                    {{ $openDamageReports ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total Pengguna --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 border-l-4 border-blue-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-700">
                                <x-heroicon-o-users class="w-6 h-6" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 uppercase tracking-wider">Total
                                    Pengguna</p>
                                <p class="text-3xl font-bold text-blue-700">
                                    {{ $totalUsers ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Laporan Selesai (Bulan Ini) --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <x-heroicon-o-check-circle class="w-6 h-6" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 uppercase tracking-wider">Laporan
                                    Selesai (Bulan Ini)</p>
                                <p class="text-3xl font-bold text-green-600">
                                    {{ $reportsCompletedThisMonth ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- [BARU] Section Link Cepat --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-700 mb-4">
                        Akses Cepat
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 text-center">

                        {{-- Link ke Lokasi --}}
                        <a href="{{ route('admin.locations.index') }}"
                            class="p-4 bg-slate-50 hover:bg-blue-100 rounded-lg transition duration-300 ease-in-out flex flex-col items-center justify-center space-y-2">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-700">
                                <x-heroicon-o-building-office class="w-7 h-7" />
                            </div>
                            <span class="text-sm font-medium text-slate-700">Kelola Lokasi</span>
                        </a>

                        {{-- Link ke Pengguna --}}
                        <a href="{{ route('admin.users.index') }}"
                            class="p-4 bg-slate-50 hover:bg-blue-100 rounded-lg transition duration-300 ease-in-out flex flex-col items-center justify-center space-y-2">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-700">
                                <x-heroicon-o-users class="w-7 h-7" />
                            </div>
                            <span class="text-sm font-medium text-slate-700">Kelola Pengguna</span>
                        </a>

                        {{-- Link ke Laporan Kerusakan --}}
                        <a href="{{ route('admin.damages.index') }}"
                            class="p-4 bg-slate-50 hover:bg-amber-100 rounded-lg transition duration-300 ease-in-out flex flex-col items-center justify-center space-y-2">
                            <div class="p-3 rounded-full bg-amber-100 text-amber-600">
                                <x-heroicon-o-exclamation-triangle class="w-7 h-7" />
                            </div>
                            <span class="text-sm font-medium text-slate-700">Laporan Kerusakan</span>
                        </a>

                        {{-- Link ke Notifikasi --}}
                        <a href="{{ route('admin.notifications.index') }}"
                            class="p-4 bg-slate-50 hover:bg-indigo-100 rounded-lg transition duration-300 ease-in-out flex flex-col items-center justify-center space-y-2">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-700">
                                <x-heroicon-o-bell class="w-7 h-7" />
                            </div>
                            <span class="text-sm font-medium text-slate-700">Lihat Notifikasi</span>
                        </a>

                    </div>
                </div>
            </div>

            {{-- Bagian Detail Status Laporan dan Pengguna --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Status Laporan Kerusakan --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-700 mb-4 border-b pb-2 border-slate-200">
                            Notifikasi Laporan Kerusakan</h3>
                        @if ($damageReportsByStatus && $damageReportsByStatus->sum() > 0)
                            <ul class="space-y-3">
                                @foreach ($damageReportsByStatus as $status => $count)
                                    <li class="flex justify-between items-center text-sm py-1">
                                        <span
                                            class="text-gray-600">{{ Str::title(str_replace('_', ' ', $status)) }}</span>
                                        <span
                                            class="font-medium py-1 px-3 rounded-full text-xs
                                            @if ($status == 'dilaporkan') bg-yellow-100 text-yellow-700
                                            @elseif($status == 'diverifikasi') bg-blue-100 text-blue-700
                                            @elseif($status == 'dalam_perbaikan') bg-indigo-100 text-indigo-700
                                            @elseif($status == 'selesai_diperbaiki') bg-green-100 text-green-700
                                            @elseif($status == 'dihapuskan') bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-700 @endif">
                                            {{ $count }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">Belum ada data status laporan.</p>
                        @endif
                    </div>
                </div>

                {{-- Ringkasan Pengguna --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-700 mb-4 border-b pb-2 border-slate-200">
                            Ringkasan Pengguna</h3>
                        @if ($usersByRole && $usersByRole->sum() > 0)
                            <ul class="space-y-3 text-sm">
                                @foreach ($usersByRole as $role => $count)
                                    <li class="flex justify-between items-center py-1">
                                        <span class="text-gray-600">{{ Str::title($role) }}</span>
                                        <span
                                            class="font-medium text-gray-800 bg-slate-100 py-1 px-3 rounded-full text-xs">{{ $count }}</span>
                                    </li>
                                @endforeach
                                <li class="flex justify-between items-center pt-2 mt-2 border-t border-slate-200">
                                    <span class="text-gray-600 font-semibold">Total Pengguna</span>
                                    <span
                                        class="font-bold text-gray-800 bg-slate-200 py-1 px-3 rounded-full text-xs">{{ $totalUsers ?? 0 }}</span>
                                </li>
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">Belum ada data pengguna.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Daftar Aktivitas Terbaru --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Lokasi Terbaru Ditambahkan --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-700 mb-4 border-b pb-2 border-slate-200">
                            5 Lokasi Terbaru</h3>
                        @if ($recentLocations && $recentLocations->count() > 0)
                            <ul class="divide-y divide-slate-200">
                                @foreach ($recentLocations as $location)
                                    <li
                                        class="py-3 flex justify-between items-start hover:bg-blue-50 px-2 -mx-2 rounded-md">
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">
                                                {{ $location->name }}</p>
                                            <p class="text-xs text-slate-500">
                                                {{ Str::limit($location->description, 50) }}
                                            </p>
                                        </div>
                                        <span
                                            class="text-xs text-slate-500 whitespace-nowrap ml-4">{{ $location->created_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">Tidak ada lokasi baru.</p>
                        @endif
                    </div>
                </div>

                {{-- Laporan Kerusakan Terbaru --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-700 mb-4 border-b pb-2 border-slate-200">
                            5 Laporan Kerusakan Terbaru</h3>
                        @if ($recentDamageReports && $recentDamageReports->count() > 0)
                            <ul class="divide-y divide-slate-200">
                                @foreach ($recentDamageReports as $report)
                                    <li class="py-3 hover:bg-blue-50 px-2 -mx-2 rounded-md">
                                        <div class="flex justify-between items-start">
                                            <p class="text-sm font-medium text-slate-800">
                                                {{ Str::limit($report->location->name ?? 'Lokasi tidak diketahui', 25) }}
                                                -
                                                <span
                                                    class="font-normal text-slate-600">{{ Str::limit($report->description, 30) }}</span>
                                            </p>
                                            <span
                                                class="text-xs text-slate-500 whitespace-nowrap ml-4">{{ ($report->reported_at ?? $report->created_at)->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1">
                                            Pelapor: {{ $report->reported_by ?? 'N/A' }} | Status:
                                            <span
                                                class="font-semibold
                                                @if ($report->status == 'dilaporkan') text-yellow-600
                                                @elseif($report->status == 'diverifikasi') text-blue-600
                                                @elseif($report->status == 'dalam_perbaikan') text-indigo-600
                                                @elseif($report->status == 'selesai_diperbaiki') text-green-600
                                                @elseif($report->status == 'dihapuskan') text-red-600
                                                @else text-gray-600 @endif">
                                                {{ Str::title(str_replace('_', ' ', $report->status)) }}
                                            </span>
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">Tidak ada laporan kerusakan baru.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
