<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationService
{
    /**
     * Mengambil notifikasi yang sudah difilter dan dipaginasi untuk seorang pengguna.
     *
     * @param User $user Pengguna yang notifikasinya akan diambil.
     * @param array $filters Filter yang akan diterapkan.
     * @param int $perPage Jumlah item per halaman.
     * @return LengthAwarePaginator
     */
    public function getFilteredNotifications(User $user, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $user->notifications()->latest();

        // Terapkan filter pencarian
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('data->message', 'like', "%{$search}%")
                    ->orWhere('data->location_name', 'like', "%{$search}%")
                    ->orWhere('data->reporter_name', 'like', "%{$search}%");
            });
        }

        // Terapkan filter status dibaca
        if ($filters['read_status'] === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($filters['read_status'] === 'unread') {
            $query->whereNull('read_at');
        }

        // Terapkan filter severity
        if (!empty($filters['severity'])) {
            $query->where('data->severity', $filters['severity']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(User $user, string $notificationId): bool
    {
        $notification = $user->notifications()->find($notificationId);

        // Jika notifikasi tidak ditemukan, kembalikan false.
        if (!$notification) {
            return false;
        }

        // Jalankan aksi untuk menandai sudah dibaca.
        $notification->markAsRead();

        // Setelah aksi berhasil, kembalikan true secara eksplisit.
        return true;
    }

    /**
     * Menandai notifikasi sebagai belum dibaca.
     */
    public function markAsUnread(User $user, string $notificationId): bool
    {
        // Metode ini sudah benar, tidak perlu diubah.
        $notification = $user->notifications()->find($notificationId);
        if ($notification) {
            $notification->update(['read_at' => null]);
            return true;
        }
        return false;
    }

    /**
     * Menandai semua notifikasi yang belum dibaca sebagai sudah dibaca.
     */
    public function markAllAsRead(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }

    /**
     * Menghapus sebuah notifikasi.
     */
    public function deleteNotification(User $user, string $notificationId): bool
    {
        $notification = $user->notifications()->find($notificationId);
        return $notification ? $notification->delete() : false;
    }

    /**
     * Menghapus semua notifikasi milik pengguna.
     */
    public function deleteAllNotifications(User $user): void
    {
        $user->notifications()->delete();
    }
}
