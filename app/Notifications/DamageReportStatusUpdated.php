<?php

namespace App\Notifications;

use App\Models\DamageReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DamageReportStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public DamageReport $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(DamageReport $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // Kita hanya akan menggunakan notifikasi database untuk saat ini
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $locationName = $this->report->location->name ?? 'Lokasi tidak diketahui';
        $newStatus = str_replace('_', ' ', $this->report->status);

        return [
            'report_id' => $this->report->id,
            'location_name' => $locationName,
            'new_status' => $newStatus,
            'message' => "Status laporan Anda untuk '{$locationName}' telah diperbarui menjadi '{$newStatus}'.",
            'type' => 'damage_report_status_updated',
        ];
    }
}