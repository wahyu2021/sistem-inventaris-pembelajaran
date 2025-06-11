<?php

namespace App\Livewire\Mahasiswa;

use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;

class ItemViewer extends Component
{
    use WithPagination;

    public $search = '';
    public $quickFilter = '';
    public $searchResults = []; // *** BARU: Properti untuk menyimpan hasil saran pencarian ***
    public $selectedLocationId = null; // *** BARU: Opsional, jika Anda ingin melacak lokasi yang benar-benar dipilih dari saran ***
    public $selectedLocationName = ''; // *** BARU: Opsional, untuk menampilkan nama lokasi yang dipilih

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage(); // Reset paginasi saat pencarian berubah

        // *** LOGIKA SUGGESTION BARU ***
        if (strlen($this->search) >= 2) { // Minimal 2 karakter untuk memulai pencarian
            $this->searchResults = Location::where('name', 'like', '%' . $this->search . '%')
                ->limit(5) // Batasi jumlah saran yang ditampilkan
                ->get();
        } else {
            $this->searchResults = []; // Kosongkan saran jika teks terlalu pendek
        }
        $this->selectedLocationId = null; // Reset pilihan saat mengetik lagi
        $this->selectedLocationName = '';
    }

    public function updatingQuickFilter()
    {
        $this->resetPage();
        $this->search = ''; // Reset pencarian teks jika filter cepat digunakan
        $this->searchResults = []; // Sembunyikan saran
        $this->selectedLocationId = null;
        $this->selectedLocationName = '';
    }

    // *** METODE BARU: Untuk memilih saran dari daftar ***
    public function selectLocationFromSearch($locationId, $locationName)
    {
        $this->selectedLocationId = $locationId;
        $this->selectedLocationName = $locationName;
        $this->search = $locationName; // Isi input search dengan nama yang dipilih
        $this->searchResults = []; // Sembunyikan daftar saran
    }

    public function render()
    {
        $query = Location::orderBy('name', 'asc');

        // Logika pencarian berdasarkan input teks $search
        // Jika ada lokasi yang dipilih dari saran, prioritaskan itu
        if ($this->selectedLocationId) {
            $query->where('id', $this->selectedLocationId);
        } elseif (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Logika filter cepat berdasarkan $quickFilter
        if (!empty($this->quickFilter)) {
            if ($this->quickFilter === 'lab') {
                $query->where('name', 'like', '%Lab%');
            } elseif ($this->quickFilter === 'ruang_teori') {
                $query->where('name', 'like', '%Ruang Teori%');
            }
        }

        $locations = $query->paginate(12);

        return view('livewire.mahasiswa.item-viewer', [
            'locations' => $locations,
        ])->layout('layouts.app');
    }
}
