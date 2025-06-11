<?php

namespace App\Services;

use App\Models\DamageReport;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminDashboardService
{
    /**
     * Mengambil data untuk kartu statistik utama.
     */
    public function getStats(): array
    {
        $openStatuses = ['dilaporkan', 'diverifikasi', 'dalam_perbaikan'];

        return [
            'totalLocations' => Location::count(),
            'openDamageReports' => DamageReport::whereIn('status', $openStatuses)->count(),
            'totalUsers' => User::count(),
            'reportsCompletedThisMonth' => DamageReport::where('status', 'selesai_diperbaiki')
                ->whereYear('resolved_at', now()->year)
                ->whereMonth('resolved_at', now()->month)
                ->count(),
        ];
    }

    /**
     * Mengambil data ringkasan untuk daftar aktivitas.
     */
    public function getSummaries(): array
    {
        return [
            'damageReportsByStatus' => DamageReport::query()
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->orderByRaw("FIELD(status, 'dilaporkan', 'diverifikasi', 'dalam_perbaikan', 'selesai_diperbaiki', 'dihapuskan')")
                ->pluck('count', 'status'),
            'usersByRole' => User::query()
                ->selectRaw('role, count(*) as count')
                ->groupBy('role')
                ->pluck('count', 'role'),
        ];
    }

    /**
     * Mengambil dan memformat data untuk semua grafik.
     */
    public function getChartData(Collection $statusSummary): array
    {
        // 1. Grafik Distribusi Status (menggunakan data yang sudah di-query)
        $statusData = $statusSummary->mapWithKeys(fn($count, $status) => [Str::title(str_replace('_', ' ', $status)) => $count])->all();

        // 2. Grafik Lokasi Paling Bermasalah
        $locationData = DamageReport::query()
            ->select('location_id', DB::raw('count(*) as count'))
            ->with('location:id,name')
            ->groupBy('location_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // 3. Grafik Tren Laporan per Bulan
        $monthData = DamageReport::query()
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('count', 'month');

        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $key = $month->format('Y-m');
            $months->put($key, $monthData->get($key, 0));
        }

        return [
            'reportsByStatusChart' => [
                'labels' => array_keys($statusData),
                'data' => array_values($statusData),
            ],
            'reportsByLocationChart' => [
                'labels' => $locationData->pluck('location.name')->all(),
                'data' => $locationData->pluck('count')->all(),
            ],
            'reportsByMonthChart' => [
                'labels' => $months->keys()->map(fn($m) => Carbon::parse($m)->translatedFormat('M Y'))->all(),
                'data' => $months->values()->all(),
            ],
        ];
    }

    /**
     * Mengambil data aktivitas terbaru.
     */
    public function getRecentActivities(): array
    {
        return [
            'recentLocations' => Location::latest()->take(5)->get(),
            'recentDamageReports' => DamageReport::with(['location:id,name', 'userReportedBy:id,name'])
                ->latest('reported_at')
                ->take(5)
                ->get(),
        ];
    }
}
