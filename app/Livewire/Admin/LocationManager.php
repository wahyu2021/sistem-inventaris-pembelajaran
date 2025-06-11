<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Location;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LocationManager extends Component
{
    use WithFileUploads, WithPagination;

    // Properti untuk form modal
    public $locationId;
    public $name;
    public $capacity;
    public $description;
    public $image; // Untuk menyimpan path gambar yang sudah ada saat edit
    public $newImage; // Untuk upload gambar baru

    // Properti untuk UI & Filter
    public $isOpen = false; // Status modal create/edit
    public $search = ''; // Untuk input teks pencarian (nama atau deskripsi)

    // *** HAPUS filterCategory atau quickFilter karena tidak ada di spesifikasi Anda ***
    // public $filterCategory = '';
    // public $filterQuick = '';

    // *** HAPUS referensi kategori karena tidak ada tabel kategori ***
    // public $categories;


    protected $paginationTheme = 'tailwind'; // Menggunakan tema Tailwind untuk paginasi

    // Listener untuk reset paginasi saat pencarian berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Metode mount() tidak diperlukan jika tidak ada inisialisasi kompleks
    // public function mount()
    // {
    //     // Jika Anda memiliki inisialisasi lain yang diperlukan saat komponen dimuat
    //     // Anda bisa tambahkan di sini. Contoh: $this->categories = Category::all();
    // }

    protected function rules()
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
            'newImage' => 'nullable|image|max:2048',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama lokasi wajib diisi.',
        'name.unique' => 'Nama lokasi sudah ada.',
        'description.required' => 'Deskripsi lokasi wajib diisi.',
        'newImage.image' => 'File harus berupa gambar.',
        'newImage.max' => 'Ukuran gambar maksimal 2MB.',
        'capacity.integer' => 'Kapasitas harus berupa angka.',
        'capacity.min' => 'Kapasitas tidak boleh kurang dari 0.',
    ];

    public function render()
    {
        $query = Location::query();

        // Logika pencarian berdasarkan nama ATAU deskripsi
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $locations = $query->orderBy('name', 'asc')->paginate(10); // Paginate 10 item per halaman

        return view('livewire.admin.location-manager', [
            'locations' => $locations, // Pastikan variabel dikirim ke view
        ])->layout('layouts.app'); // Sesuaikan dengan layout admin Anda
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->locationId = null;
        $this->name = '';
        $this->capacity = null;
        $this->description = '';
        $this->image = null;
        $this->newImage = null;
    }

    public function store()
    {
        $validatedData = $this->validate();
        $currentImagePath = $this->image; // Path gambar yang sudah ada (saat edit)

        if ($this->newImage) {
            // Hapus gambar lama jika ada gambar baru dan sedang dalam mode edit
            if ($this->locationId && $currentImagePath) {
                Storage::disk('public')->delete($currentImagePath);
            }
            // Simpan gambar baru
            $currentImagePath = $this->newImage->store('location-images', 'public');
        }

        Location::updateOrCreate(['id' => $this->locationId], [
            'name' => $validatedData['name'],
            'capacity' => $validatedData['capacity'],
            'description' => $validatedData['description'],
            'image' => $currentImagePath,
        ]);

        session()->flash(
            'message',
            $this->locationId ? 'Data lokasi berhasil diperbarui.' : 'Data lokasi berhasil ditambahkan.'
        );

        $this->closeModal();
        // Emit event jika ada komponen lain yang perlu di-refresh
        // $this->dispatch('locationAdded');
        // $this->dispatch('locationUpdated');
    }

    public function edit(Location $location)
    {
        $this->locationId = $location->id;
        $this->name = $location->name;
        $this->capacity = $location->capacity;
        $this->description = $location->description;
        $this->image = $location->image; // Path gambar yang sudah ada
        $this->newImage = null; // Reset newImage saat edit

        $this->openModal();
    }

    public function delete(Location $location)
    {
        // Hapus gambar dari storage jika ada
        if ($location->image) {
            Storage::disk('public')->delete($location->image);
        }

        $location->delete();
        session()->flash('message', 'Data lokasi berhasil dihapus.');
        // Emit event jika ada komponen lain yang perlu di-refresh
        // $this->dispatch('locationDeleted');
    }

    // Livewire lifecycle hook untuk membersihkan error validasi saat file diupload ulang
    public function updatingNewImage()
    {
        $this->resetErrorBag('newImage');
        $this->resetValidation('newImage');
    }
}
