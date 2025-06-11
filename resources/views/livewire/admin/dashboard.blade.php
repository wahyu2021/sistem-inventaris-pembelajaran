<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen" wire:poll.30s="loadDashboardData">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            {{-- Salam Pembuka dan Tanggal --}}
            <div class="mb-8 px-4 sm:px-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Selamat Datang Kembali,
                    {{ Auth::user()->name ?? 'Admin' }}!</h1>
                <p class="text-sm text-slate-600">Berikut adalah ringkasan aktivitas sistem Anda hari ini,
                    {{ now()->translatedFormat('l, d F Y') }}.</p>
            </div>

            {{-- Kartu Statistik Utama --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-dashboard-stat-card icon="heroicon-o-building-office" title="Total Lokasi" :value="$totalLocations"
                    color="blue" />
                <x-dashboard-stat-card icon="heroicon-o-exclamation-triangle" title="Laporan Rusak Terbuka"
                    :value="$openDamageReports" color="amber" />
                <x-dashboard-stat-card icon="heroicon-o-users" title="Total Pengguna" :value="$totalUsers"
                    color="blue" />
                <x-dashboard-stat-card icon="heroicon-o-check-circle" title="Laporan Selesai (Bulan Ini)"
                    :value="$reportsCompletedThisMonth" color="green" />
            </div>

            {{-- Akses Cepat --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-700 mb-4">Akses Cepat</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <x-dashboard-quick-link :route="route('admin.locations.index')" icon="heroicon-o-building-office"
                            title="Kelola Lokasi" color="blue" />
                        <x-dashboard-quick-link :route="route('admin.users.index')" icon="heroicon-o-users" title="Kelola Pengguna"
                            color="blue" />
                        <x-dashboard-quick-link :route="route('admin.damages.index')" icon="heroicon-o-exclamation-triangle"
                            title="Laporan Kerusakan" color="amber" />
                        <x-dashboard-quick-link :route="route('admin.notifications.index')" icon="heroicon-o-bell" title="Lihat Notifikasi"
                            color="indigo" />
                    </div>
                </div>
            </div>

            {{-- Bagian Detail Status Laporan dan Pengguna --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Status Laporan Kerusakan --}}
                <x-dashboard-summary-card title="Notifikasi Laporan Kerusakan" :items="$damageReportsByStatus">
                    @foreach ($damageReportsByStatus as $status => $count)
                        <li class="flex justify-between items-center text-sm py-1">
                            <span class="text-gray-600">{{ Str::title(str_replace('_', ' ', $status)) }}</span>
                            <span
                                class="font-medium py-1 px-3 rounded-full text-xs @if ($status == 'dilaporkan') bg-yellow-100 text-yellow-700 @elseif($status == 'diverifikasi') bg-blue-100 text-blue-700 @elseif($status == 'dalam_perbaikan') bg-indigo-100 text-indigo-700 @elseif($status == 'selesai_diperbaiki') bg-green-100 text-green-700 @elseif($status == 'dihapuskan') bg-red-100 text-red-700 @else bg-gray-100 text-gray-700 @endif">
                                {{ $count }}
                            </span>
                        </li>
                    @endforeach
                </x-dashboard-summary-card>

                {{-- Ringkasan Pengguna --}}
                <x-dashboard-summary-card title="Ringkasan Pengguna" :items="$usersByRole">
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
                </x-dashboard-summary-card>
            </div>

            {{-- Daftar Aktivitas Terbaru --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <x-dashboard-recent-activity-card title="5 Lokasi Terbaru" :items="$recentLocations">
                    @foreach ($recentLocations as $location)
                        <li class="py-3 flex justify-between items-start hover:bg-blue-50 px-2 -mx-2 rounded-md">
                            <div>
                                <p class="text-sm font-medium text-slate-800">{{ $location->name }}</p>
                                <p class="text-xs text-slate-500">{{ Str::limit($location->description, 50) }}</p>
                            </div>
                            <span
                                class="text-xs text-slate-500 whitespace-nowrap ml-4">{{ $location->created_at->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </x-dashboard-recent-activity-card>

                <x-dashboard-recent-activity-card title="5 Laporan Kerusakan Terbaru" :items="$recentDamageReports">
                    @foreach ($recentDamageReports as $report)
                        <li class="py-3 hover:bg-blue-50 px-2 -mx-2 rounded-md">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium text-slate-800">
                                    {{ Str::limit($report->location->name ?? 'N/A', 25) }} -
                                    <span
                                        class="font-normal text-slate-600">{{ Str::limit($report->description, 30) }}</span>
                                </p>
                                <span
                                    class="text-xs text-slate-500 whitespace-nowrap ml-4">{{ ($report->reported_at ?? $report->created_at)->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">
                                Pelapor: {{ $report->reported_by ?? 'N/A' }} | Status:
                                <span
                                    class="font-semibold @if ($report->status == 'dilaporkan') text-yellow-600 @elseif($report->status == 'diverifikasi') text-blue-600 @elseif($report->status == 'dalam_perbaikan') text-indigo-600 @elseif($report->status == 'selesai_diperbaiki') text-green-600 @elseif($report->status == 'dihapuskan') text-red-600 @else text-gray-600 @endif">
                                    {{ Str::title(str_replace('_', ' ', $report->status)) }}
                                </span>
                            </p>
                        </li>
                    @endforeach
                </x-dashboard-recent-activity-card>
            </div>
        </div>
    </div>
</div>
