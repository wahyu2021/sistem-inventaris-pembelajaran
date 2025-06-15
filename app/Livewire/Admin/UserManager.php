<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserManager extends Component
{
    use WithPagination;

    public UserForm $form;

    // Properti UI
    public bool $isModalOpen = false;
    public string $search = '';
    public string $filterRole = '';
    public array $allowedRoles = ['admin', 'mahasiswa'];

    // Properti untuk Modal Konfirmasi Hapus
    public ?int $userToDeleteId = null;
    public bool $confirmingUserDeletion = false;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak diizinkan mengakses halaman ini.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterRole(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->form->reset();
        $this->isModalOpen = true;
    }

    public function openEditModal(User $user): void
    {
        $this->form->setUser($user);
        $this->isModalOpen = true;
    }

    public function save(UserService $userService): void
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Aksi tidak diizinkan.');
        $this->form->validate();

        try {
            if ($this->form->userId) {
                $userService->updateUser($this->form->user, $this->form->data());
                session()->flash('message', 'Data pengguna berhasil diperbarui.');
            } else {
                $userService->createUser($this->form->data());
                session()->flash('message', 'Pengguna baru berhasil ditambahkan.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $this->isModalOpen = false;
    }

    /**
     * Membuka modal konfirmasi penghapusan pengguna.
     */
    public function confirmUserDeletion(int $id): void
    {
        $this->userToDeleteId = $id;
        $this->confirmingUserDeletion = true;
    }

    /**
     * Menghapus pengguna setelah dikonfirmasi.
     */
    public function deleteUser(UserService $userService): void
    {   
        abort_if(!auth()->user()->isAdmin(), 403, 'Aksi tidak diizinkan.');
        $user = User::find($this->userToDeleteId);
        if (!$user) {
            session()->flash('error', 'Pengguna tidak ditemukan.');
            $this->confirmingUserDeletion = false;
            return;
        }

        try {
            $userService->deleteUser($user);
            session()->flash('message', 'Pengguna berhasil dihapus.');
        } catch (Exception $e) {
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }

        $this->confirmingUserDeletion = false;
        $this->userToDeleteId = null;
    }

    public function render()
    {
        $query = User::query()
            // ->where('id', '!=', Auth::id()) // Jangan tampilkan user yang sedang login
            ->when(
                $this->search,
                fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->filterRole,
                fn($q) =>
                $q->where('role', $this->filterRole)
            );

        return view('livewire.admin.user-manager', [
            'users' => $query->orderBy('name', 'asc')->paginate(10),
        ])->layout('layouts.app');
    }
}
