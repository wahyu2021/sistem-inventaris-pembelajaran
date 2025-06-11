<?php

namespace App\Livewire\Admin;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationManager extends Component
{
    use WithPagination;

    // Properti untuk filter
    public string $search = '';
    public string $filterReadStatus = '';
    public string $filterSeverity = '';
    public array $allowedSeverities = ['ringan', 'sedang', 'berat'];

    // Properti untuk Modal Detail
    public ?array $selectedNotificationData = null;
    public bool $isDetailModalOpen = false;

    // Properti untuk Modal Konfirmasi Hapus
    public ?string $notificationToDeleteId = null;
    public bool $confirmingNotificationDeletion = false;
    public bool $confirmingDeleteAll = false;

    protected $paginationTheme = 'tailwind';

    public function updating($property): void
    {
        if (in_array($property, ['search', 'filterReadStatus', 'filterSeverity'])) {
            $this->resetPage();
        }
    }

    // Aksi-aksi yang didelegasikan ke Service

    public function markAsRead(NotificationService $notificationService, string $notificationId): void
    {
        $notificationService->markAsRead(Auth::user(), $notificationId);
    }

    public function markAsUnread(NotificationService $notificationService, string $notificationId): void
    {
        $notificationService->markAsUnread(Auth::user(), $notificationId);
    }

    public function markAllAsRead(NotificationService $notificationService): void
    {
        $notificationService->markAllAsRead(Auth::user());
        session()->flash('message', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }

    public function deleteSelectedNotification(NotificationService $notificationService): void
    {
        if ($this->notificationToDeleteId) {
            $notificationService->deleteNotification(Auth::user(), $this->notificationToDeleteId);
            session()->flash('message', 'Notifikasi berhasil dihapus.');
        }
        $this->confirmingNotificationDeletion = false;
        $this->notificationToDeleteId = null;
    }

    public function deleteAllNotifications(NotificationService $notificationService): void
    {
        $notificationService->deleteAllNotifications(Auth::user());
        session()->flash('message', 'Semua notifikasi berhasil dihapus.');
        $this->confirmingDeleteAll = false;
    }

    // Fungsi untuk membuka modal

    public function openDetailModal(string $notificationId): void
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $this->selectedNotificationData = $notification->data;
            if (!$notification->read_at) {
                $this->markAsRead(new NotificationService, $notificationId);
            }
            $this->isDetailModalOpen = true;
        }
    }

    public function confirmDelete(string $notificationId): void
    {
        $this->notificationToDeleteId = $notificationId;
        $this->confirmingNotificationDeletion = true;
    }

    public function confirmDeleteAll(): void
    {
        $this->confirmingDeleteAll = true;
    }

    public function render(NotificationService $notificationService)
    {
        $filters = [
            'search' => $this->search,
            'read_status' => $this->filterReadStatus,
            'severity' => $this->filterSeverity,
        ];

        $notifications = $notificationService->getFilteredNotifications(Auth::user(), $filters);

        return view('livewire.admin.notification-manager', [
            'notifications' => $notifications,
        ])->layout('layouts.app');
    }
}
