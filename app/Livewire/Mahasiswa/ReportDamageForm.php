<?php

namespace App\Livewire\Mahasiswa;

use App\Livewire\Forms\ReportDamageByStudentForm;
use App\Models\Location;
use App\Services\DamageReportService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReportDamageForm extends Component
{
    use WithFileUploads;

    public ReportDamageByStudentForm $form;

    public ?Location $location = null;

    public function mount(Location $location = null): void
    {
        if ($location->exists) {
            $this->location = $location;
            $this->form->setLocation($location);
        }
    }

    /**
     * Mengirim laporan dengan mendelegasikan ke service.
     */
    public function submit(DamageReportService $damageReportService)
    {
        $this->form->validate();

        // Siapkan data untuk service, termasuk nama pelapor
        $reportData = $this->form->only(['selectedLocationId', 'description', 'severity']);
        $reportData['reported_by_name'] = Auth::user()->name;

        try {
            $damageReportService->createReport($reportData, $this->form->newImageDamage);

            session()->flash('message', 'Laporan kerusakan berhasil dikirim.');
            return redirect()->route('mahasiswa.damages.my');
        } catch (Exception $e) {
            session()->flash('error', 'Gagal mengirim laporan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $headerTitle = $this->location
            ? 'Laporkan Kerusakan: ' . $this->location->name
            : 'Laporkan Kerusakan Lokasi';

        return view('livewire.mahasiswa.report-damage-form', [
            'headerTitle' => $headerTitle
        ])->layout('layouts.app');
    }
}
