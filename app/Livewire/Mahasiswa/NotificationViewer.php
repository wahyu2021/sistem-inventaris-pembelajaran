<?php

namespace App\Livewire\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationViewer extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public function markAsRead(string $notificationId): void
    {
        Auth::user()->notifications()->find($notificationId)?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
        session()->flash('message', 'Semua notifikasi telah ditandai terbaca.');
    }

    public function deleteNotification(string $notificationId): void
    {
        Auth::user()->notifications()->find($notificationId)?->delete();
        session()->flash('message', 'Notifikasi telah dihapus.');
    }

    public function render()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view('livewire.mahasiswa.notification-viewer', [
            'notifications' => $notifications,
        ])->layout('layouts.app');
    }
}
