<?php

namespace App\Observers;

use App\Models\User;
use App\Models\DamageReport;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DamageReportStatusUpdated;
use App\Notifications\DamageReportSubmittedNotification;

class DamageReportObserver
{
    /**
     * Handle the DamageReport "created" event.
     */
    public function created(DamageReport $damageReport): void
    {
        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            // Kirim hanya ID-nya saja, bukan seluruh objek.
            Notification::send($admins, new DamageReportSubmittedNotification($damageReport->id));
        }
    }

    /**
     * Handle the DamageReport "updated" event.
     * Kirim notifikasi ke mahasiswa yang melapor jika status berubah.
     */
    public function updated(DamageReport $damageReport): void
    {
        // Periksa apakah kolom 'status' benar-benar berubah
        if ($damageReport->wasChanged('status')) {
            // Dapatkan user yang membuat laporan
            $reporter = $damageReport->userReportedBy;

            // Pastikan user tersebut ada dan bukan admin yang mengubah laporannya sendiri
            if ($reporter && $reporter->role === 'mahasiswa') {
                $reporter->notify(new DamageReportStatusUpdated($damageReport));
            }
        }
    }

    /**
     * Handle the DamageReport "deleted" event.
     */
    public function deleted(DamageReport $damageReport): void
    {
        //
    }

    /**
     * Handle the DamageReport "restored" event.
     */
    public function restored(DamageReport $damageReport): void
    {
        //
    }

    /**
     * Handle the DamageReport "force deleted" event.
     */
    public function forceDeleted(DamageReport $damageReport): void
    {
        //
    }
}
