<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Location;
use App\Models\DamageReport;
use App\Models\User;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DamageReportSubmittedNotification;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ReportDamageForm extends Component
{
    use WithFileUploads;

    public ?Location $location = null;
    public $locationId = null;

    public $locationSearch = '';
    public $locationSearchResults = [];
    public $selectedLocationId = null;
    public $selectedLocationName = '';

    public $description;
    public $severity = 'ringan';
    public $newImageDamage;

    public $allowedSeverities = [];

    public function mount($locationParam = null, Request $request)
    {
        // Inisialisasi tingkat kerusakan
        if (property_exists(DamageReport::class, 'allowedSeverities') && is_array(DamageReport::$allowedSeverities)) {
            $this->allowedSeverities = DamageReport::$allowedSeverities;
        } else {
            $this->allowedSeverities = ['ringan', 'sedang', 'parah'];
            \Log::warning('ReportDamageForm: DamageReport::$allowedSeverities tidak tersedia. Menggunakan default.');
        }

        // Prioritas: 1. Route model binding / props  2. Query string
        if ($locationParam instanceof Location) {
            $this->location = $locationParam;
        } elseif (is_numeric($locationParam)) {
            $this->location = Location::find($locationParam);
        } elseif ($request->has('location')) {
            $locationFromQuery = Location::find($request->query('location'));
            if ($locationFromQuery) {
                $this->location = $locationFromQuery;
            }
        }

        if ($this->location) {
            $this->locationId = $this->location->id;
            $this->selectedLocationId = $this->location->id;
            $this->selectedLocationName = $this->location->name;
        }
    }

    protected function rules()
    {
        return [
            'selectedLocationId' => 'required|exists:locations,id',
            'description' => 'required|string|min:10|max:1000',
            'severity' => ['required', Rule::in($this->allowedSeverities)],
            'newImageDamage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected $messages = [
        'selectedLocationId.required' => 'Lokasi wajib dipilih atau sudah ditentukan dari URL.',
        'selectedLocationId.exists' => 'Lokasi tidak valid.',
        'description.required' => 'Deskripsi wajib diisi.',
        'description.min' => 'Deskripsi minimal 10 karakter.',
        'severity.required' => 'Tingkat kerusakan wajib dipilih.',
        'newImageDamage.image' => 'File harus berupa gambar.',
        'newImageDamage.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
        'newImageDamage.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function updatedLocationSearch()
    {
        if (!$this->locationId) {
            $this->selectedLocationId = null;
            $this->selectedLocationName = '';
            $this->resetErrorBag('selectedLocationId');

            if (strlen($this->locationSearch) >= 2) {
                $this->locationSearchResults = Location::where('name', 'like', '%' . $this->locationSearch . '%')
                    ->orderBy('name')
                    ->limit(5)
                    ->get();
            } else {
                $this->locationSearchResults = [];
            }
        }
    }

    public function selectLocationFromSearch($locationId, $locationName)
    {
        $this->selectedLocationId = $locationId;
        $this->selectedLocationName = $locationName;
        $this->locationSearch = $locationName;
        $this->locationSearchResults = [];
        $this->resetErrorBag('selectedLocationId');
    }

    public function submitReport()
    {
        $validatedData = $this->validate();
        $imagePath = null;

        if ($this->newImageDamage) {
            $imagePath = $this->newImageDamage->store('damage-reports/locations', 'public');
        }

        $reportedByName = Auth::check() ? Auth::user()->name : 'Guest';
        $reportedByIdUser = Auth::id();

        $damageReport = DamageReport::create([
            'location_id' => $validatedData['selectedLocationId'],
            'reported_by' => $reportedByName,
            'reported_by_id_user' => $reportedByIdUser,
            'description' => $validatedData['description'],
            'severity' => $validatedData['severity'],
            'status' => 'dilaporkan',
            'image_damage' => $imagePath,
            'reported_at' => now(),
        ]);

        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new DamageReportSubmittedNotification($damageReport));
        }

        $this->resetFormFields();
        session()->flash('message', 'Laporan kerusakan berhasil dikirim.');
        return redirect()->route('mahasiswa.locations.index');
    }

    public function resetFormFields()
    {
        $this->reset('description', 'severity', 'newImageDamage', 'locationSearch');
        if (!$this->locationId) {
            $this->reset('selectedLocationId', 'selectedLocationName', 'locationSearchResults');
        }
    }

    public function render()
    {
        $headerTitle = $this->selectedLocationName
            ? 'Laporkan Kerusakan: ' . $this->selectedLocationName
            : 'Laporkan Kerusakan Lokasi';

        return view('livewire.mahasiswa.report-damage-form', [
            'headerTitle' => $headerTitle
        ])->layout('layouts.app');
    }
}
