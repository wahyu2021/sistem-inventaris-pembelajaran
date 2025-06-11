<?php

namespace App\Livewire\Admin;

use App\Models\DamageReport;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Livewire\Component;

class Dashboard extends Component
{
    // Gunakan Type Hinting untuk kejelasan dan keamanan tipe data.
    public int $totalLocations;
    public int $openDamageReports;
    public int $totalUsers;
    public int $reportsCompletedThisMonth;
    public SupportCollection $damageReportsByStatus;
    public SupportCollection $usersByRole;
    public Collection $recentLocations;
    public Collection $recentDamageReports;

    /**
     * Metode mount dijalankan sekali saat komponen diinisialisasi.
     * Mengambil semua data yang dibutuhkan untuk dashboard.
     */
    public function mount(): void
    {
        $this->loadDashboardData();
    }

    /**
     * Memuat ulang semua data dashboard.
     * Dapat dipanggil untuk me-refresh data secara dinamis.
     */
    public function loadDashboardData(): void
    {
        // Memanggil metode privat untuk setiap set data.
        $this->totalLocations = $this->getTotalLocations();
        $this->openDamageReports = $this->getOpenDamageReports();
        $this->totalUsers = $this->getTotalUsers();
        $this->reportsCompletedThisMonth = $this->getCompletedReportsThisMonth();
        $this->damageReportsByStatus = $this->getDamageReportsByStatus();
        $this->usersByRole = $this->getUsersByRole();
        $this->recentLocations = $this->getRecentLocations();
        $this->recentDamageReports = $this->getRecentDamageReports();
    }

    // Metode privat untuk setiap kueri data. Ini membuat `loadDashboardData` lebih bersih.

    private function getTotalLocations(): int
    {
        return Location::count();
    }

    private function getOpenDamageReports(): int
    {
        // Status 'terbuka' didefinisikan secara eksplisit.
        $openStatuses = ['dilaporkan', 'diverifikasi', 'dalam_perbaikan'];
        return DamageReport::whereIn('status', $openStatuses)->count();
    }

    private function getTotalUsers(): int
    {
        return User::count();
    }

    private function getCompletedReportsThisMonth(): int
    {
        // Kueri yang lebih spesifik untuk laporan yang selesai bulan ini.
        return DamageReport::where('status', 'selesai_diperbaiki')
            ->whereYear('resolved_at', now()->year)
            ->whereMonth('resolved_at', now()->month)
            ->count();
    }

    private function getDamageReportsByStatus(): SupportCollection
    {
        // Menggunakan raw query untuk performa dan pengurutan custom.
        return DamageReport::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->orderByRaw("FIELD(status, 'dilaporkan', 'diverifikasi', 'dalam_perbaikan', 'selesai_diperbaiki', 'dihapuskan')")
            ->pluck('count', 'status');
    }

    private function getUsersByRole(): SupportCollection
    {
        return User::query()
            ->selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role');
    }

    private function getRecentLocations(int $limit = 5): Collection
    {
        // Mengambil lokasi terbaru dengan limit.
        return Location::latest()->take($limit)->get();
    }

    private function getRecentDamageReports(int $limit = 5): Collection
    {
        // Eager load relasi 'location' untuk menghindari N+1 query.
        return DamageReport::with('location:id,name')
            ->latest('reported_at')
            ->take($limit)
            ->get();
    }

    /**
     * Render view komponen Livewire.
     */
    public function render()
    {
        // Semua properti sudah diisi di `mount()`, jadi view langsung merendernya.
        return view('livewire.admin.dashboard')->layout('layouts.app');
    }
}
