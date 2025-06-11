<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\DamageReport; // Pastikan model ini ada
use App\Models\Location; // *** BARU: Import model Location ***
use App\Models\User; // *** Opsional: Jika Anda ingin mengakses data user yang melaporkan melalui relasi reported_by_id_user ***

class Dashboard extends Component
{
    public $recentDamageReports;
    public $totalMyReports;
    public $myOpenReports;
    public $myResolvedReports;

    public function mount()
    {
        $user = Auth::user();

        // Ambil beberapa laporan kerusakan terbaru dari pengguna ini
        // *** PENTING: Mengubah with('item:id,name') menjadi with('location:id,name') ***
        // *** Opsional: Menambahkan with('userReportedBy:id,name') jika Anda ingin menampilkan nama user dari relasi reported_by_id_user ***
        $this->recentDamageReports = $user->damageReports() // Asumsi relasi damageReports() ada di model User
            ->with(['location:id,name', 'userReportedBy:id,name']) // Eager load nama lokasi dan nama user yang melaporkan
            ->latest('reported_at') // Urutkan berdasarkan tanggal lapor terbaru
            ->take(5) // Ambil 5 laporan terbaru
            ->get();

        // Statistik Laporan Pengguna (logika ini tetap relevan dan tidak perlu diubah)
        $this->totalMyReports = $user->damageReports()->count();
        $this->myOpenReports = $user->damageReports()
            ->whereIn('status', ['dilaporkan', 'diverifikasi', 'dalam_perbaikan'])
            ->count();
        $this->myResolvedReports = $user->damageReports()
            ->whereIn('status', ['selesai_diperbaiki', 'dihapuskan'])
            ->count();
    }

    public function render()
    {
        // Pastikan view livewire.mahasiswa.dashboard-page menampilkan data lokasi dan pelapor yang benar
        return view('livewire.mahasiswa.dashboard')
            ->layout('layouts.app'); // Menggunakan layout default Jetstream (x-app-layout)
    }
}