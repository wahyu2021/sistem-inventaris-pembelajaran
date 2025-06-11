<?php

namespace App\Livewire\Admin;

use Livewire\Component;
// use Illuminate\Notifications\DatabaseNotification; // Tidak dibutuhkan jika filter type dihapus
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification as Notification;
// use Illuminate\Support\Str; // Tidak digunakan secara eksplisit

class NotificationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterReadStatus = '';
    public $filterSeverity = '';
    public $allowedSeverities = ['ringan', 'sedang', 'berat']; // Opsi untuk filter severity

    public $selectedNotificationData;
    public $isDetailModalOpen = false;

    protected $paginationTheme = 'tailwind';
    protected $listeners = ['$refresh'];

    // Livewire Component
    public $isDeleteModalOpen = false;
    public $notificationToDeleteId = null;
    public $isDeleteAllModalOpen = false;


    public function render()
    {
        $user = Auth::user();
        $query = $user->notifications()->latest();

        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('data->message', 'like', '%' . $search . '%')
                    ->orWhere('data->location_name', 'like', '%' . $search . '%')
                    ->orWhere('data->reporter_name', 'like', '%' . $search . '%')
                    ->orWhere('data->severity', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan status baca
        if ($this->filterReadStatus === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($this->filterReadStatus === 'unread') {
            $query->whereNull('read_at');
        }

        // Filter berdasarkan tingkat kerusakan (severity)
        if (!empty($this->filterSeverity)) {
            $query->where('data->severity', $this->filterSeverity);
        }

        $notifications = $query->paginate(15);

        return view('livewire.admin.notification-manager', [
            'notifications' => $notifications,
            'allowedSeverities' => $this->allowedSeverities,
        ])->layout('layouts.app');
    }

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->markAsRead();
            $this->dispatch('$refresh');
        }
    }

    public function markAsUnread($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->update(['read_at' => null]);
            $this->dispatch('$refresh');
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        session()->flash('message', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
        $this->dispatch('$refresh');
    }

    public function deleteNotification($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->delete();
            session()->flash('message', 'Notifikasi berhasil dihapus.');
            $this->dispatch('$refresh');
        }
    }

    public function deleteAllNotifications()
    {
        Auth::user()->notifications()->delete();
        session()->flash('message', 'Semua notifikasi berhasil dihapus.');
        $this->dispatch('$refresh');
    }

    public function showNotificationDetail($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $this->selectedNotificationData = $notification->data;
            $this->isDetailModalOpen = true;
            if (!$notification->read_at) {
                // Panggil markAsRead dan refresh agar status di tabel juga update
                $this->markAsRead($notificationId);
                // $this->dispatch('$refresh'); // markAsRead sudah melakukan dispatch refresh
            }
        }
    }

    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->selectedNotificationData = null;
    }

    public function confirmDelete($id)
    {
        $this->notificationToDeleteId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function cancelDelete()
    {
        $this->isDeleteModalOpen = false;
        $this->notificationToDeleteId = null;
    }

    public function deleteConfirmed()
    {
        Notification::find($this->notificationToDeleteId)?->delete();
        $this->isDeleteModalOpen = false;
        $this->notificationToDeleteId = null;
        session()->flash('message', 'Notifikasi berhasil dihapus.');
    }

    public function confirmDeleteAll()
    {
        $this->isDeleteAllModalOpen = true;
    }

    public function cancelDeleteAll()
    {
        $this->isDeleteAllModalOpen = false;
    }

    public function deleteAllConfirmed()
    {
        Notification::truncate(); // atau Notification::query()->delete();
        $this->isDeleteAllModalOpen = false;
        session()->flash('message', 'Semua notifikasi berhasil dihapus.');
    }
}
