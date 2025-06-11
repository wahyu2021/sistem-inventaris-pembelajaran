<?php

namespace App\Livewire\Admin; // Sesuaikan namespace jika direktori berbeda

use Livewire\Component;
use App\Models\Location;    // Pastikan path model benar
use App\Models\DamageReport; // Pastikan path model benar
use App\Models\User;         // Pastikan path model benar
use Illuminate\Support\Facades\DB; // Untuk query yang lebih kompleks jika perlu
use Carbon\Carbon;

class Dashboard extends Component
{
    // Properti untuk menyimpan data statistik
    public $totalLocations;
    public $openDamageReports;
    public $totalUsers;
    public $reportsCompletedThisMonth;

    public $damageReportsByStatus;
    public $usersByRole;

    public $recentLocations;
    public $recentDamageReports;

    /**
     * Logika yang dijalankan saat komponen pertama kali dimuat.
     * Mengambil semua data yang dibutuhkan untuk dashboard.
     */
    public function mount()
    {
        $this->loadDashboardData();
    }

    /**
     * Metode untuk memuat atau memuat ulang data dashboard.
     * Bisa dipanggil lagi jika ada aksi yang memerlukan refresh data.
     */
    public function loadDashboardData()
    {
        // Kartu Statistik Utama
        $this->totalLocations = Location::count();
        $this->openDamageReports = DamageReport::whereNotIn('status', ['selesai_diperbaiki', 'dihapuskan'])->count();
        $this->totalUsers = User::count();
        $this->reportsCompletedThisMonth = DamageReport::where('status', 'selesai_diperbaiki')
            ->whereMonth('resolved_at', now()->month)
            ->whereYear('resolved_at', now()->year)
            ->count();

        // Detail Status Laporan Kerusakan
        $this->damageReportsByStatus = DamageReport::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            // Urutan custom untuk status agar lebih logis di tampilan
            ->orderByRaw("FIELD(status, 'dilaporkan', 'diverifikasi', 'dalam_perbaikan', 'selesai_diperbaiki', 'dihapuskan')")
            ->pluck('count', 'status');

        // Ringkasan Pengguna berdasarkan Peran
        $this->usersByRole = User::query()
            ->selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role');

        // Aktivitas Terbaru
        $this->recentLocations = Location::latest()->take(5)->get();

        // Eager load relasi 'location' untuk menghindari N+1 query.
        // Asumsi 'reported_by' adalah string, jadi tidak perlu eager load 'reporter' untuk nama.
        $this->recentDamageReports = DamageReport::with('location')
            ->latest('reported_at') // Atau 'created_at'
            ->take(5)
            ->get();
    }

    /**
     * Metode render untuk menampilkan view.
     */
    public function render()
    {
        // Data sudah dimuat di mount() dan tersedia sebagai properti publik
        return view('livewire.admin.dashboard') // Pastikan nama view ini sesuai
            ->layout('layouts.app'); // Sesuaikan dengan layout utama Anda
    }
}