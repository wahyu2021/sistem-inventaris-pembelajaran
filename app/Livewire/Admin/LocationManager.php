<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\LocationForm;
use App\Models\Location;
use App\Services\LocationService;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class LocationManager extends Component
{
    use WithPagination, WithFileUploads;

    public LocationForm $form;

    public bool $isModalOpen = false;
    public string $search = '';

    // Properti untuk Modal Konfirmasi Hapus
    public ?int $locationToDeleteId = null;
    public bool $confirmingLocationDeletion = false;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->form->reset();
        $this->isModalOpen = true;
    }

    public function openEditModal(Location $location): void
    {
        $this->form->setLocation($location);
        $this->isModalOpen = true;
    }

    public function save(LocationService $locationService): void
    {
        $this->form->validate();

        try {
            if ($this->form->locationId) {
                $locationService->updateLocation($this->form->location, $this->form->data(), $this->form->newImage);
                session()->flash('message', 'Data lokasi berhasil diperbarui.');
            } else {
                $locationService->createLocation($this->form->data(), $this->form->newImage);
                session()->flash('message', 'Data lokasi berhasil ditambahkan.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $this->isModalOpen = false;
    }

    /**
     * Membuka modal konfirmasi penghapusan lokasi.
     */
    public function confirmLocationDeletion(int $id): void
    {
        $this->locationToDeleteId = $id;
        $this->confirmingLocationDeletion = true;
    }

    /**
     * Menghapus lokasi setelah dikonfirmasi.
     */
    public function deleteLocation(LocationService $locationService): void
    {
        $location = Location::find($this->locationToDeleteId);
        if (!$location) {
            session()->flash('error', 'Lokasi tidak ditemukan.');
            $this->confirmingLocationDeletion = false;
            return;
        }

        try {
            $locationService->deleteLocation($location);
            session()->flash('message', 'Data lokasi berhasil dihapus.');
        } catch (Exception $e) {
            session()->flash('error', 'Gagal menghapus lokasi: ' . $e->getMessage());
        }

        $this->confirmingLocationDeletion = false;
        $this->locationToDeleteId = null;
    }

    public function render()
    {
        $query = Location::query()
            ->when(
                $this->search,
                fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
            );

        return view('livewire.admin.location-manager', [
            'locations' => $query->orderBy('name', 'asc')->paginate(10),
        ])->layout('layouts.app');
    }
}
