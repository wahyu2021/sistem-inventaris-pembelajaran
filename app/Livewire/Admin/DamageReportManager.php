<?php

namespace App\Livewire\Admin;

use App\Models\Location;
use App\Models\User;
use Livewire\Component;
use App\Models\DamageReport;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification; // Jika Anda akan menggunakan notifikasi
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DamageReportManager extends Component
{
    use WithPagination, WithFileUploads;

    // Properti untuk form (Create/Edit)
    public $reportId;
    // public $location_id; // <-- Tidak lagi digunakan langsung, digantikan oleh selectedLocationId
    public $reported_by_name; // Ini adalah input teks string untuk nama pelapor
    public $description;
    public $severity = 'ringan';
    public $status = 'dilaporkan';
    public $image_damage; // Path gambar yang sudah tersimpan
    public $newImageDamage; // File upload baru

    // *** BARU: Properti untuk input Lokasi dengan Suggestion di form modal ***
    public $locationSearch = ''; // Input teks untuk mencari lokasi
    public $locationSearchResults = []; // Hasil saran lokasi
    public $selectedLocationId = null; // ID lokasi yang dipilih (ini yang akan disimpan ke DB)
    public $selectedLocationName = ''; // Nama lokasi yang dipilih (untuk ditampilkan di input)


    // Properti untuk UI & Filter (di luar modal)
    public $isOpen = false; // Status modal create/edit
    public $search = ''; // Pencarian teks umum (untuk tabel)
    public $filterStatus = ''; // Filter berdasarkan status (untuk tabel)
    public $filterLocation = ''; // Filter berdasarkan lokasi (ID) (untuk tabel)
    public $filterSeverity = ''; // Filter berdasarkan tingkat kerusakan (untuk tabel)

    public $locationsForForm; // Daftar lokasi untuk dropdown filter utama (di luar modal)
    public $allowedSeverities = []; // Daftar tingkat kerusakan yang diizinkan (untuk form & filter)
    public $allowedStatuses = ['dilaporkan', 'diverifikasi', 'dalam_perbaikan', 'selesai_diperbaiki', 'dihapuskan']; // Daftar status yang diizinkan

    // Properti untuk Modal Detail Laporan (Read-only)
    public $selectedReportDetail;
    public $isReportDetailModalOpen = false;

    protected $paginationTheme = 'tailwind';
    protected $listeners = [
        'reportAdded' => '$refresh',
        'reportUpdated' => '$refresh',
        'reportDeleted' => '$refresh',
        'closeModal' => 'closeModal',
    ];

    // Reset paginasi saat filter atau pencarian umum (tabel) berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
    public function updatingFilterLocation()
    {
        $this->resetPage();
    }
    public function updatingFilterSeverity()
    {
        $this->resetPage();
    }

    // *** BARU: Metode untuk mengupdate saran pencarian lokasi di form modal ***
    public function updatedLocationSearch()
    {
        // Reset ID dan Nama pilihan saat pengguna mulai mengetik lagi
        $this->selectedLocationId = null;
        $this->selectedLocationName = '';
        $this->resetErrorBag('selectedLocationId'); // Hapus error validasi jika user mulai mengetik lagi

        if (strlen($this->locationSearch) >= 2) { // Minimal 2 karakter untuk memulai pencarian
            $this->locationSearchResults = Location::where('name', 'like', '%' . $this->locationSearch . '%')
                ->limit(5) // Batasi jumlah saran
                ->get();
        } else {
            $this->locationSearchResults = []; // Kosongkan saran jika teks terlalu pendek
        }
    }

    // *** BARU: Metode untuk memilih lokasi dari saran di form modal ***
    public function selectLocationFromModalSearch($locationId, $locationName)
    {
        $this->selectedLocationId = $locationId;
        $this->selectedLocationName = $locationName;
        $this->locationSearch = $locationName; // Isi input dengan nama yang dipilih
        $this->locationSearchResults = []; // Sembunyikan saran
    }


    protected function rules()
    {
        return [
            // Validasi diubah dari 'location_id' menjadi 'selectedLocationId'
            'selectedLocationId' => 'required|exists:locations,id',
            'reported_by_name' => 'required|string|max:255',
            'description' => 'required|string|min:10|max:1000',
            'severity' => ['required', Rule::in(DamageReport::$allowedSeverities)],
            // Ambil $allowedStatuses dari properti kelas atau langsung dari model
            'status' => ['required', Rule::in($this->allowedStatuses)],
            'newImageDamage' => 'nullable|image|max:2048',
        ];
    }

    protected $messages = [
        // Pesan validasi diubah untuk 'selectedLocationId'
        'selectedLocationId.required' => 'Lokasi wajib dipilih.',
        'selectedLocationId.exists' => 'Lokasi yang dipilih tidak valid.',
        'reported_by_name.required' => 'Nama pelapor wajib diisi.',
        'reported_by_name.string' => 'Nama pelapor harus berupa teks.',
        'reported_by_name.max' => 'Nama pelapor maksimal 255 karakter.',
        'description.required' => 'Deskripsi kerusakan wajib diisi.',
        'description.min' => 'Deskripsi minimal 10 karakter.',
        'severity.required' => 'Tipe kerusakan wajib dipilih.',
        'severity.in' => 'Pilihan tipe kerusakan tidak valid.',
        'status.required' => 'Status laporan wajib dipilih.',
        'status.in' => 'Pilihan status laporan tidak valid.',
        'newImageDamage.image' => 'File harus berupa gambar.',
        'newImageDamage.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function mount()
    {
        // Untuk dropdown filter lokasi di tabel (di luar modal)
        $this->locationsForForm = Location::orderBy('name')->get(['id', 'name']);
        $this->allowedSeverities = DamageReport::$allowedSeverities;

        // Set default reported_by_name dengan nama user yang login saat komponen pertama kali dimuat
        if (Auth::check()) {
            $this->reported_by_name = Auth::user()->name;
        } else {
            $this->reported_by_name = '';
        }
    }

    public function render()
    {
        // Eager load relasi 'location' dan 'userReportedBy' (untuk reported_by_id_user)
        $query = DamageReport::with(['location:id,name', 'userReportedBy:id,name'])
            ->orderBy('reported_at', 'desc');

        // Filter dan Pencarian umum (tabel)
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                    ->orWhere('reported_by', 'like', '%' . $this->search . '%')
                    ->orWhereHas('userReportedBy', function ($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('location', function ($locationQuery) {
                        $locationQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }
        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }
        if (!empty($this->filterLocation)) {
            $query->where('location_id', $this->filterLocation);
        }
        if (!empty($this->filterSeverity)) {
            $query->where('severity', $this->filterSeverity);
        }

        $reports = $query->paginate(10);

        return view('livewire.admin.damage-report-manager', [
            'reports' => $reports,
            'allLocations' => $this->locationsForForm, // Untuk filter di luar modal
            'allSeverities' => $this->allowedSeverities,
            'allStatuses' => $this->allowedStatuses,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields(); // Reset semua input form
        // Reset properties khusus search/select lokasi di form modal
        $this->locationSearch = '';
        $this->locationSearchResults = [];
        $this->selectedLocationId = null;
        $this->selectedLocationName = '';

        if (Auth::check()) {
            $this->reported_by_name = Auth::user()->name;
        } else {
            $this->reported_by_name = '';
        }
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
        $this->resetInputFields(); // Reset input saat modal ditutup
        // Reset properties khusus search/select lokasi di form modal saat menutup
        $this->locationSearch = '';
        $this->locationSearchResults = [];
        $this->selectedLocationId = null;
        $this->selectedLocationName = '';
    }

    private function resetInputFields()
    {
        $this->reportId = null;
        // $this->location_id = null; // Ini sudah tidak digunakan langsung di form, digantikan selectedLocationId
        $this->reported_by_name = '';
        $this->description = '';
        $this->severity = 'ringan';
        $this->status = 'dilaporkan';
        $this->image_damage = null;
        $this->newImageDamage = null;
    }

    public function store()
    {
        $validatedData = $this->validate();
        $imagePath = $this->image_damage;

        if ($this->newImageDamage) {
            if ($this->reportId && $this->image_damage) {
                Storage::disk('public')->delete($this->image_damage);
            }
            $imagePath = $this->newImageDamage->store('damage-reports', 'public');
        }

        $reportedByIdUser = null;
        // Hanya cek user jika reported_by_name tidak kosong.
        if (!empty($validatedData['reported_by_name'])) {
            $userByName = User::where('name', $validatedData['reported_by_name'])->first();
            if ($userByName) {
                $reportedByIdUser = $userByName->id;
            }
        }

        $damageReportData = [
            'location_id' => $validatedData['selectedLocationId'], // <-- Menggunakan selectedLocationId
            'reported_by' => $validatedData['reported_by_name'],
            'reported_by_id_user' => $reportedByIdUser,
            'description' => $validatedData['description'],
            'severity' => $validatedData['severity'],
            'status' => $validatedData['status'],
            'image_damage' => $imagePath,
            'reported_at' => $this->reportId ? (DamageReport::find($this->reportId)->reported_at ?? now()) : now(),
            'resolved_at' => null,
        ];

        if ($validatedData['status'] === 'selesai_diperbaiki' || $validatedData['status'] === 'dihapuskan') {
            if ($this->reportId) {
                $existingReport = DamageReport::find($this->reportId);
                $damageReportData['resolved_at'] = $existingReport->resolved_at ?? now();
            } else {
                $damageReportData['resolved_at'] = now();
            }
        }

        DamageReport::updateOrCreate(['id' => $this->reportId], $damageReportData);

        session()->flash('message', $this->reportId ? 'Laporan kerusakan berhasil diperbarui.' : 'Laporan kerusakan berhasil ditambahkan.');

        $this->closeModal();
        $this->dispatch($this->reportId ? 'reportUpdated' : 'reportAdded');
    }

    public function edit($id)
    {
        // Eager load relasi 'location' dan 'userReportedBy'
        $report = DamageReport::with(['location', 'userReportedBy'])->findOrFail($id);
        $this->reportId = $report->id;

        // Set properties untuk lokasi input search dengan suggestion
        $this->selectedLocationId = $report->location_id;
        $this->selectedLocationName = $report->location->name ?? ''; // Ambil nama dari relasi lokasi
        $this->locationSearch = $this->selectedLocationName; // Isi input search dengan nama lokasi

        $this->reported_by_name = $report->userReportedBy->name ?? $report->reported_by;
        $this->description = $report->description;
        $this->severity = $report->severity;
        $this->status = $report->status;
        $this->image_damage = $report->image_damage;
        $this->newImageDamage = null;

        $this->openModal();
    }

    public function delete($id)
    {
        $report = DamageReport::findOrFail($id);
        if ($report->image_damage) {
            Storage::disk('public')->delete($report->image_damage);
        }
        $report->delete();
        session()->flash('message', 'Laporan kerusakan berhasil dihapus.');
        $this->dispatch('reportDeleted');
    }

    public function updatingNewImageDamage()
    {
        $this->resetErrorBag('newImageDamage');
        $this->resetValidation('newImageDamage');
    }

    public function showReportDetail($reportId)
    {
        // Eager load relasi 'location' dan 'userReportedBy'
        $this->selectedReportDetail = DamageReport::with(['location', 'userReportedBy'])->find($reportId);
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
