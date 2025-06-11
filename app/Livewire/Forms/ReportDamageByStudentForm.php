<?php

namespace App\Livewire\Forms;

use App\Models\DamageReport;
use App\Models\Location;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ReportDamageByStudentForm extends Form
{
    // Properti Form
    public ?int $selectedLocationId = null;
    public string $description = '';
    public string $severity = 'ringan';
    public ?UploadedFile $newImageDamage = null;

    // Properti untuk Pencarian Lokasi di dalam form
    public string $locationSearch = '';
    public $locationSearchResults = [];

    /**
     * Mengisi form dari model Location (saat datang dari URL).
     */
    public function setLocation(Location $location): void
    {
        $this->selectedLocationId = $location->id;
        $this->locationSearch = $location->name;
    }

    /**
     * Aturan validasi untuk form.
     */
    public function rules(): array
    {
        return [
            'selectedLocationId' => 'required|exists:locations,id',
            'description' => 'required|string|min:10|max:1000',
            'severity' => ['required', Rule::in(DamageReport::$allowedSeverities)],
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
            'description' => 'Deskripsi Kerusakan',
            'severity' => 'Tingkat Kerusakan',
            'newImageDamage' => 'Foto Kerusakan',
        ];
    }

    /**
     * Logika untuk mencari lokasi.
     */
    public function searchLocations(): void
    {
        if (strlen($this->locationSearch) < 2) {
            $this->locationSearchResults = [];
            return;
        }

        $this->locationSearchResults = Location::where('name', 'like', '%' . $this->locationSearch . '%')
            ->limit(5)->get(['id', 'name']);
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
}
