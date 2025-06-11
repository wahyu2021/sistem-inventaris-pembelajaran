<?php

namespace App\Observers;

use App\Models\DamageReport;
use App\Models\User;
use App\Notifications\DamageReportSubmittedNotification;
use Illuminate\Support\Facades\Notification;

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
     */
    public function updated(DamageReport $damageReport): void
    {
        //
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
