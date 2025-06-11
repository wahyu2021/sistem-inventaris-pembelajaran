<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\DamageReport;
use App\Models\Location;
use Illuminate\Contracts\View\View;

class MyDamageReports extends Component
{
    use WithPagination;

    // Properti filter
    public string $search = '';
    public string $filterStatus = '';
    public string $filterSeverity = '';
    public string $filterLocation = '';

    // Properti untuk Modal Detail
    public ?DamageReport $selectedReportDetail = null;
    public bool $isReportDetailModalOpen = false;

    protected string $paginationTheme = 'tailwind';

    /**
     * Reset paginasi setiap kali ada perubahan pada properti filter.
     */
    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'filterStatus', 'filterSeverity', 'filterLocation'])) {
            $this->resetPage();
        }
    }

    /**
     * Mengambil data laporan dan menampilkannya di modal.
     */
    public function showReportDetail(int $reportId): void
    {
        // Pastikan laporan ini milik user yang sedang login dan eager load relasinya
        $this->selectedReportDetail = Auth::user()->damageReports()
            ->with(['location', 'userReportedBy'])
            ->find($reportId);

        if ($this->selectedReportDetail) {
            $this->isReportDetailModalOpen = true;
        }
    }

    /**
     * Menutup modal detail.
     */
    public function closeReportDetailModal(): void
    {
        $this->isReportDetailModalOpen = false;
        $this->selectedReportDetail = null;
    }

    /**
     * Merender komponen.
     */
    public function render(): View
    {
        $query = Auth::user()->damageReports()
            ->with(['location:id,name'])
            ->when($this->search, function ($q, $search) {
                $q->where(function ($subQ) use ($search) {
                    $subQ->where('description', 'like', '%' . $search . '%')
                        ->orWhereHas('location', fn($locQ) => $locQ->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterSeverity, fn($q) => $q->where('severity', $this->filterSeverity))
            ->when($this->filterLocation, fn($q) => $q->where('location_id', $this->filterLocation));

        return view('livewire.mahasiswa.my-damage-reports', [
            'myReports' => $query->latest('reported_at')->paginate(10),
            'allLocations' => Location::orderBy('name')->get(['id', 'name']),
            'allowedSeverities' => DamageReport::$allowedSeverities,
            'allowedStatuses' => ['dilaporkan', 'diverifikasi', 'dalam_perbaikan', 'selesai_diperbaiki', 'dihapuskan'],
        ])->layout('layouts.app');
    }
}
