<?php

namespace App\Livewire\Admin; // Sesuaikan namespace jika perlu

use Livewire\Component;
use App\Models\User; // Pastikan model User Anda ada di App\Models\User
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // Untuk mencegah delete diri sendiri

class UserManager extends Component
{
    use WithPagination;

    // Properti untuk form modal
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role;

    // Properti untuk UI
    public $isOpen = false;
    public $search = '';
    public $filterRole = ''; // Untuk filter berdasarkan role

    // Daftar peran yang diizinkan
    public $allowedRoles = ['admin', 'mahasiswa']; // Sesuaikan dengan kebutuhan Anda

    protected $paginationTheme = 'tailwind'; // Atau 'tailwind'

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->userId),
            ],
            'role' => ['required', Rule::in($this->allowedRoles)],
        ];

        // Aturan password: wajib saat membuat, opsional saat edit
        if (!$this->userId) { // Mode Create
            $rules['password'] = 'required|string|min:8|confirmed';
        } else { // Mode Edit
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Nama pengguna wajib diisi.',
        'email.required' => 'Alamat email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Alamat email sudah terdaftar.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'role.required' => 'Peran pengguna wajib dipilih.',
        'role.in' => 'Peran pengguna tidak valid.',
    ];

    public function render()
    {
        $query = User::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->filterRole)) {
            $query->where('role', $this->filterRole);
        }

        $users = $query->orderBy('name', 'asc')->paginate(10);

        return view('livewire.admin.user-manager', [ // Pastikan path view benar
            'users' => $users,
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
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'mahasiswa'; // Default role saat membuat baru
    }

    public function store()
    {
        $validatedData = $this->validate();

        $userData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'role' => $validatedData['role'],
        ];

        // Hanya update password jika diisi (terutama untuk mode edit)
        if (!empty($validatedData['password'])) {
            $userData['password'] = Hash::make($validatedData['password']);
        }

        User::updateOrCreate(['id' => $this->userId], $userData);

        session()->flash(
            'message',
            $this->userId ? 'Data pengguna berhasil diperbarui.' : 'Pengguna baru berhasil ditambahkan.'
        );

        $this->closeModal();
    }

    public function edit(User $user)
    {
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = ''; // Kosongkan field password saat edit
        $this->password_confirmation = '';

        $this->openModal();
    }

    public function delete(User $user)
    {
        // Opsional: Mencegah pengguna menghapus akunnya sendiri
        if (Auth::id() === $user->id) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            return;
        }

        // Opsional: Mencegah penghapusan admin terakhir (jika diperlukan logika tambahan)

        $user->delete();
        session()->flash('message', 'Pengguna berhasil dihapus.');
    }
}
