<?php

namespace App\Livewire\Forms;

use App\Models\Location;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Livewire\Form;

class LocationForm extends Form
{
    public ?Location $location;

    public ?int $locationId = null;
    public string $name = '';
    public ?int $capacity = null;
    public string $description = '';
    
    // Properti untuk gambar
    public ?string $image = null; // Menyimpan path gambar yang sudah ada
    public ?UploadedFile $newImage = null; // Untuk file upload baru

    /**
     * Aturan validasi untuk form lokasi.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('locations')->ignore($this->locationId),
            ],
            'capacity' => 'nullable|integer|min:0',
            'description' => 'required|string|max:65535',
            'newImage' => 'nullable|image|max:2048', // Maksimal 2MB
        ];
    }

    /**
     * Mengisi properti form dari model Location yang ada (untuk mode edit).
     */
    public function setLocation(Location $location): void
    {
        $this->location = $location;
        $this->locationId = $location->id;
        $this->name = $location->name;
        $this->capacity = $location->capacity;
        $this->description = $location->description;
        $this->image = $location->image;
        $this->newImage = null; // Selalu reset file upload baru saat edit
    }

    /**
     * Mengambil data bersih dari form untuk disimpan ke database.
     */
    public function data(): array
    {
        return $this->only(['name', 'capacity', 'description']);
    }
}