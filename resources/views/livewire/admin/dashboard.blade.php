<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            {{-- Salam Pembuka dan Kartu Statistik --}}
            <div class="mb-8 px-4 sm:px-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Selamat Datang Kembali,
                    {{ Auth::user()->name ?? 'Admin' }}!</h1>
                <p class="text-sm text-slate-600">Berikut adalah ringkasan aktivitas sistem Anda hari ini,
                    {{ now()->translatedFormat('l, d F Y') }}.</p>
            </div>
            
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

            {{-- Bagian Grafik Analitik --}}
            <div class="mt-8 mb-8">
                <h2 class="px-4 sm:px-0 text-xl font-bold text-slate-700">Analitik Sistem</h2>
            </div>
            @if (collect($reportsByStatusChart)->isNotEmpty() && !empty($reportsByStatusChart['labels']))
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="bg-white p-4 rounded-lg shadow-lg" wire:ignore>
                        {{-- PERBAIKAN: Menggunakan kutip satu (') untuk x-data --}}
                        <div
                            x-data='{
                            labels: @json($reportsByStatusChart['labels'] ?? []),
                            data: @json($reportsByStatusChart['data'] ?? []),
                            init() {
                                new Chart(this.$refs.statusChart, {
                                    type: "doughnut",
                                    data: {
                                        labels: this.labels,
                                        datasets: [{
                                            data: this.data,
                                            backgroundColor: ["#FBBF24", "#60A5FA", "#818CF8", "#34D399", "#F87171", "#9CA3AF"],
                                            hoverOffset: 4
                                        }]
                                    },
                                    options: {
                                        responsive: true, maintainAspectRatio: false,
                                        plugins: {
                                            legend: { position: "top" },
                                            title: { display: true, text: "Distribusi Status Laporan", font: { size: 16 } }
                                        }
                                    }
                                })
                            }
                         }'>
                            <canvas x-ref="statusChart" style="height: 350px;"></canvas>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-lg" wire:ignore>
                        <div
                            x-data='{
                            labels: @json($reportsByLocationChart['labels'] ?? []),
                            data: @json($reportsByLocationChart['data'] ?? []),
                            init() {
                                new Chart(this.$refs.locationChart, {
                                    type: "bar",
                                    data: {
                                        labels: this.labels,
                                        datasets: [{
                                            label: "Jumlah Laporan",
                                            data: this.data,
                                            backgroundColor: "rgba(59, 130, 246, 0.5)",
                                            borderColor: "rgba(59, 130, 246, 1)",
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        indexAxis: "y", responsive: true, maintainAspectRatio: false,
                                        plugins: {
                                            legend: { display: false },
                                            title: { display: true, text: "Top 5 Lokasi Laporan Terbanyak", font: { size: 16 } }
                                        }
                                    }
                                })
                            }
                         }'>
                            <canvas x-ref="locationChart" style="height: 350px;"></canvas>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-lg lg:col-span-2" wire:ignore>
                        {{-- PERBAIKAN: Menggunakan kutip satu (') untuk x-data --}}
                        <div
                            x-data='{
                            labels: @json($reportsByMonthChart['labels'] ?? []),
                            data: @json($reportsByMonthChart['data'] ?? []),
                            init() {
                                new Chart(this.$refs.monthChart, {
                                    type: "line",
                                    data: {
                                        labels: this.labels,
                                        datasets: [{
                                            label: "Laporan Baru",
                                            data: this.data,
                                            fill: false,
                                            borderColor: "rgb(22, 163, 74)",
                                            tension: 0.1
                                        }]
                                    },
                                    options: {
                                        responsive: true, maintainAspectRatio: false,
                                        plugins: {
                                            legend: { display: false },
                                            title: {
                                                display: true,
                                                text: "Tren Laporan per Bulan (12 Bulan Terakhir)",
                                                font: { size: 16 }
                                            }
                                        }
                                    }
                                })
                            }
                         }'>
                            <canvas x-ref="monthChart" style="height: 350px;"></canvas>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-blue-50 text-blue-700 p-4 rounded-lg text-center">
                    Belum ada data yang cukup untuk menampilkan analitik.
                </div>
            @endif


            {{-- Bagian Aktivitas Terbaru --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
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
                                Pelapor: {{ $report->userReportedBy->name ?? $report->reported_by }} | Status:
                                <x-damage-status-badge :status="$report->status" />
                            </p>
                        </li>
                    @endforeach
                </x-dashboard-recent-activity-card>
            </div>
        </div>
    </div>
</div>
