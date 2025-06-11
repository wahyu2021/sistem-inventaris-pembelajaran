<?php

namespace App\Livewire\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    // Gunakan typed properties untuk kejelasan kode
    public int $totalMyReports = 0;
    public int $myOpenReports = 0;
    public int $myResolvedReports = 0;
    public Collection $recentDamageReports;

    /**
     * Dijalankan saat komponen di-mount.
     */
    public function mount(): void
    {
        $this->loadStats();
        $this->loadRecentReports();
    }

    /**
     * Mengambil statistik laporan dengan satu kueri efisien.
     */
    private function loadStats(): void
    {
        $stats = Auth::user()->damageReports()
            ->selectRaw("
                count(*) as total,
                count(case when status in ('dilaporkan', 'diverifikasi', 'dalam_perbaikan') then 1 else null end) as open,
                count(case when status in ('selesai_diperbaiki', 'dihapuskan') then 1 else null end) as resolved
            ")
            ->first();

        $this->totalMyReports = $stats->total ?? 0;
        $this->myOpenReports = $stats->open ?? 0;
        $this->myResolvedReports = $stats->resolved ?? 0;
    }

    /**
     * Mengambil 5 laporan kerusakan terbaru.
     */
    private function loadRecentReports(): void
    {
        $this->recentDamageReports = Auth::user()->damageReports()
            ->with(['location:id,name', 'userReportedBy:id,name'])
            ->latest('reported_at')
            ->take(5)
            ->get();
    }

    /**
     * Merender view komponen.
     */
    public function render()
    {
        return view('livewire.mahasiswa.dashboard')->layout('layouts.app');
    }
}
