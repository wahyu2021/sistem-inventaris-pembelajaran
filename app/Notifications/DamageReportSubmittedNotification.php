<?php

namespace App\Notifications;

use App\Models\DamageReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Pastikan notifikasi ini adalah ShouldQueue
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

// Implementasikan ShouldQueue agar Laravel tahu untuk mengantrekannya
class DamageReportSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    // Ubah dari objek menjadi ID (integer)
    public int $reportId;

    /**
     * Create a new notification instance.
     * Terima hanya ID, bukan seluruh objek.
     */
    public function __construct(int $reportId)
    {
        $this->reportId = $reportId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $report = DamageReport::find($this->reportId);
        if (!$report) {
            return [];
        }

        $locationName = $report->location ? $report->location->name : 'Tidak diketahui';
        $reporterName = $report->reported_by ?: 'Tidak diketahui';

        return [
            'reported_id' => $report->id,
            'location_name' => $locationName,
            'reporter_name' => $reporterName,
            'severity' => $report->severity,
            'message' => "Laporan kerusakan baru untuk ruangan '{$locationName}' dari {$reporterName}.",
            'type' => 'damage_report_submitted',
        ];
    }
}
