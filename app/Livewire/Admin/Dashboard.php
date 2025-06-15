<?php

namespace App\Livewire\Admin;

use App\Services\AdminDashboardService;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection as SupportCollection;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    // Properti untuk data yang akan ditampilkan di view
    public int $totalLocations = 0;
    public int $openDamageReports = 0;
    public int $totalUsers = 0;
    public int $reportsCompletedThisMonth = 0;
    public SupportCollection $damageReportsByStatus;
    public SupportCollection $usersByRole;
    public EloquentCollection $recentLocations;
    public EloquentCollection $recentDamageReports;
    public array $reportsByStatusChart = [];
    public array $reportsByLocationChart = [];
    public array $reportsByMonthChart = [];

    public function __construct()
    {
        // Inisialisasi koleksi kosong untuk menghindari error pada render awal
        $this->damageReportsByStatus = collect();
        $this->usersByRole = collect();
        $this->recentLocations = new EloquentCollection();
        $this->recentDamageReports = new EloquentCollection();
    }

    /**
     * Mount komponen dan muat semua data melalui service.
     */
    public function mount(AdminDashboardService $dashboardService): void
    {   if (!Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak diizinkan mengakses halaman ini.');
        } else{
            $this->loadDashboardData($dashboardService);
        }
    }

    /**
     * Memuat semua data dashboard dari service dan menetapkannya ke properti publik.
     */
    public function loadDashboardData(AdminDashboardService $dashboardService): void
    {
        // Ambil data statistik dan ringkasan
        $stats = $dashboardService->getStats();
        $summaries = $dashboardService->getSummaries();

        // Tetapkan data ke properti
        $this->totalLocations = $stats['totalLocations'];
        $this->openDamageReports = $stats['openDamageReports'];
        $this->totalUsers = $stats['totalUsers'];
        $this->reportsCompletedThisMonth = $stats['reportsCompletedThisMonth'];
        $this->damageReportsByStatus = $summaries['damageReportsByStatus'];
        $this->usersByRole = $summaries['usersByRole'];

        // Ambil data grafik (menggunakan data ringkasan status yang sudah ada untuk efisiensi)
        $chartData = $dashboardService->getChartData($this->damageReportsByStatus);
        $this->reportsByStatusChart = $chartData['reportsByStatusChart'];
        $this->reportsByLocationChart = $chartData['reportsByLocationChart'];
        $this->reportsByMonthChart = $chartData['reportsByMonthChart'];

        // Ambil data aktivitas terbaru
        $recentActivities = $dashboardService->getRecentActivities();
        $this->recentLocations = $recentActivities['recentLocations'];
        $this->recentDamageReports = $recentActivities['recentDamageReports'];
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.app');
    }
}
