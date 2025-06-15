<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\DamageReportForm;
use App\Models\DamageReport;
use App\Models\Location;
use App\Services\DamageReportService;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Exports\DamageReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DamageReportManager extends Component
{
    use WithPagination, WithFileUploads;

    public DamageReportForm $form;

    // Properti untuk filter dan UI
    public string $search = '';
    public string $filterStatus = '';
    public string $filterLocation = '';
    public string $filterSeverity = '';
    public bool $isFormModalOpen = false;
    public bool $isDetailModalOpen = false;
    public ?DamageReport $selectedReportDetail = null;

    // Properti untuk Modal Konfirmasi Hapus
    public ?int $reportToDeleteId = null;
    public bool $confirmingReportDeletion = false;

    protected $paginationTheme = 'tailwind';
    
    public function mount()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak diizinkan mengakses halaman ini.');
        }
    }

    public function updating($property): void
    {
        if (in_array($property, ['search', 'filterStatus', 'filterLocation', 'filterSeverity'])) {
            $this->resetPage();
        }
    }

    public function openCreateModal(): void
    {
        $this->form->resetForm();
        $this->isFormModalOpen = true;
    }

    public function openEditModal(DamageReport $report): void
    {
        $this->form->setReport($report);
        $this->isFormModalOpen = true;
    }

    public function openDetailModal(DamageReport $report): void
    {
        $this->selectedReportDetail = $report->load(['location', 'userReportedBy']);
        $this->isDetailModalOpen = true;
    }

    public function save(DamageReportService $damageReportService): void
    {
        $this->form->validate();

        try {
            if ($this->form->reportId) {
                $damageReportService->updateReport($this->form->report, $this->form->data(), $this->form->newImageDamage);
                session()->flash('message', 'Laporan kerusakan berhasil diperbarui.');
            } else {
                $damageReportService->createReport($this->form->data(), $this->form->newImageDamage);
                session()->flash('message', 'Laporan kerusakan berhasil ditambahkan.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $this->isFormModalOpen = false;
    }

    /**
     * Membuka modal konfirmasi penghapusan laporan.
     */
    public function confirmReportDeletion(int $id): void
    {
        $this->reportToDeleteId = $id;
        $this->confirmingReportDeletion = true;
    }

    /**
     * Menghapus laporan setelah dikonfirmasi.
     */
    public function deleteReport(DamageReportService $damageReportService): void
    {
        $report = DamageReport::find($this->reportToDeleteId);
        if (!$report) {
            session()->flash('error', 'Laporan tidak ditemukan.');
            $this->confirmingReportDeletion = false;
            return;
        }

        try {
            $damageReportService->deleteReport($report);
            session()->flash('message', 'Laporan berhasil dihapus.');
        } catch (Exception $e) {
            session()->flash('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }

        $this->confirmingReportDeletion = false;
        $this->reportToDeleteId = null;
    }

    /**
     * Memicu unduhan file CSV berdasarkan filter yang aktif.
     */
    public function export(): BinaryFileResponse
    {
        $fileName = 'laporan-kerusakan-' . now()->format('d-m-Y') . '.xlsx';

        // Mengirim nilai filter saat ini ke kelas Export
        return Excel::download(
            new DamageReportExport(
                $this->search,
                $this->filterStatus,
                $this->filterLocation,
                $this->filterSeverity
            ),
            $fileName
        );
    }

    public function render()
    {
        // ... (render method tidak berubah)
        $query = DamageReport::with(['location:id,name', 'userReportedBy:id,name'])
            ->when($this->search, function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                    ->orWhere('reported_by', 'like', '%' . $this->search . '%')
                    ->orWhereHas('location', fn($sq) => $sq->where('name', 'like', '%' . $this->search . '%'));
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterLocation, fn($q) => $q->where('location_id', $this->filterLocation))
            ->when($this->filterSeverity, fn($q) => $q->where('severity', $this->filterSeverity));

        return view('livewire.admin.damage-report-manager', [
            'reports' => $query->latest('reported_at')->paginate(10),
            'locationsForFilter' => Location::orderBy('name')->get(['id', 'name']),
            'allowedSeverities' => DamageReport::$allowedSeverities,
            'allowedStatuses' => ['dilaporkan', 'diverifikasi', 'dalam_perbaikan', 'selesai_diperbaiki', 'dihapuskan'],
        ])->layout('layouts.app');
    }
}
