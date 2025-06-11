<?php

namespace App\Livewire\Forms;

use App\Models\DamageReport;
use App\Models\Location;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Form;

class DamageReportForm extends Form
{
    public ?DamageReport $report;

    // Properti Form
    public ?int $reportId = null;
    public ?int $selectedLocationId = null;
    public string $reported_by_name = '';
    public string $description = '';
    public string $severity = 'ringan';
    public string $status = 'dilaporkan';
    public ?string $image_damage = null;
    public ?UploadedFile $newImageDamage = null;

    // Properti untuk Pencarian Lokasi di dalam Modal
    public string $locationSearch = '';
    public $locationSearchResults = [];

    /**
     * Aturan validasi untuk form.
     */
    public function rules(): array
    {
        return [
            'selectedLocationId' => 'required|exists:locations,id',
            'reported_by_name' => 'required|string|max:255',
            'description' => 'required|string|min:10|max:1000',
            'severity' => ['required', Rule::in(DamageReport::$allowedSeverities)],
            'status' => ['required', Rule::in(['dilaporkan', 'diverifikasi', 'dalam_perbaikan', 'selesai_diperbaiki', 'dihapuskan'])],
            'newImageDamage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Pesan validasi kustom.
     */
    public function validationAttributes(): array
    {
        return [
            'selectedLocationId' => 'Lokasi',
            'reported_by_name' => 'Nama Pelapor',
            'description' => 'Deskripsi Kerusakan',
            'severity' => 'Tipe Kerusakan',
            'status' => 'Status Laporan',
            'newImageDamage' => 'Foto Kerusakan',
        ];
    }

    /**
     * Mengisi form dari model yang ada untuk mode edit.
     */
    public function setReport(DamageReport $report): void
    {
        $this->report = $report;
        $this->reportId = $report->id;
        $this->selectedLocationId = $report->location_id;
        $this->locationSearch = $report->location->name ?? '';
        $this->reported_by_name = $report->userReportedBy->name ?? $report->reported_by;
        $this->description = $report->description;
        $this->severity = $report->severity;
        $this->status = $report->status;
        $this->image_damage = $report->image_damage;
    }

    /**
     * Logika untuk mencari lokasi saat user mengetik di modal.
     */
    public function searchLocations(): void
    {
        if (strlen($this->locationSearch) < 2) {
            $this->locationSearchResults = [];
            return;
        }

        $this->locationSearchResults = Location::where('name', 'like', '%' . $this->locationSearch . '%')
            ->limit(5)
            ->get();
    }

    /**
     * Memilih lokasi dari hasil pencarian.
     */
    public function selectLocation(int $id, string $name): void
    {
        $this->selectedLocationId = $id;
        $this->locationSearch = $name;
        $this->locationSearchResults = [];
        $this->resetErrorBag('selectedLocationId');
    }

    /**
     * Menyiapkan data untuk disimpan.
     */
    public function data(): array
    {
        return $this->only([
            'selectedLocationId',
            'reported_by_name',
            'description',
            'severity',
            'status',
        ]);
    }

    /**
     * Mereset semua properti form ke keadaan awal.
     */
    public function resetForm(): void
    {
        $this->reset();
        $this->reported_by_name = Auth::user()->name ?? '';
    }
}
