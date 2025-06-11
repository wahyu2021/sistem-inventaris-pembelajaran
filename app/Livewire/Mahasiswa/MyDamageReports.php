<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\DamageReport;
use App\Models\Location; // Untuk filter lokasi

class MyDamageReports extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterSeverity = '';
    public $filterLocation = ''; // Filter berdasarkan lokasi

    public $allLocations; // Daftar lokasi untuk dropdown filter
    public $allowedSeverities; // Daftar tingkat kerusakan
    public $allowedStatuses = ['dilaporkan', 'diverifikasi', 'dalam_perbaikan', 'selesai_diperbaiki', 'dihapuskan']; // Daftar status yang diizinkan

    public $selectedReportDetail; // Untuk modal detail
    public $isReportDetailModalOpen = false;

    protected $paginationTheme = 'tailwind';

    // Reset paginasi saat filter atau pencarian berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
    public function updatingFilterSeverity()
    {
        $this->resetPage();
    }
    public function updatingFilterLocation()
    {
        $this->resetPage();
    }


    public function mount()
    {
        $this->allLocations = Location::orderBy('name')->get(['id', 'name']);
        $this->allowedSeverities = DamageReport::$allowedSeverities;
    }

    public function render()
    {
        $user = Auth::user();

        $query = $user->damageReports() // Ambil laporan hanya untuk user yang login
            ->with(['location:id,name', 'userReportedBy:id,name']) // Eager load relasi
            ->orderBy('reported_at', 'desc');

        // Apply filters
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                    ->orWhere('reported_by', 'like', '%' . $this->search . '%')
                    ->orWhereHas('location', function ($locationQuery) {
                        $locationQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        if (!empty($this->filterSeverity)) {
            $query->where('severity', $this->filterSeverity);
        }

        if (!empty($this->filterLocation)) {
            $query->where('location_id', $this->filterLocation);
        }

        $myReports = $query->paginate(10); // Paginate the results

        return view('livewire.mahasiswa.my-damage-reports', [
            'myReports' => $myReports,
        ])->layout('layouts.app');
    }

    // Metode untuk menampilkan detail laporan di modal
    public function showReportDetail($reportId)
    {
        // Pastikan laporan ini milik user yang sedang login
        $this->selectedReportDetail = Auth::user()->damageReports()->with(['location', 'userReportedBy'])->find($reportId);

        if ($this->selectedReportDetail) {
            $this->isReportDetailModalOpen = true;
        }
    }

    public function closeReportDetailModal()
    {
        $this->isReportDetailModalOpen = false;
        $this->selectedReportDetail = null;
    }
}
