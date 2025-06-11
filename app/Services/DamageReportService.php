<?php

namespace App\Services;

use App\Models\DamageReport;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DamageReportService
{
    /**
     * Membuat laporan kerusakan baru.
     */
    public function createReport(array $data, ?UploadedFile $image): DamageReport
    {
        $reportData = $this->prepareReportData($data, $image);
        $reportData['reported_at'] = now();

        return DamageReport::create($reportData);
    }

    /**
     * Memperbarui laporan kerusakan yang ada.
     */
    public function updateReport(DamageReport $report, array $data, ?UploadedFile $newImage): DamageReport
    {
        $reportData = $this->prepareReportData($data, $newImage, $report->image_damage);

        // Atur tanggal selesai jika statusnya 'selesai' atau 'dihapuskan' dan belum diatur sebelumnya.
        if (in_array($data['status'], ['selesai_diperbaiki', 'dihapuskan']) && is_null($report->resolved_at)) {
            $reportData['resolved_at'] = now();
        } elseif (!in_array($data['status'], ['selesai_diperbaiki', 'dihapuskan'])) {
            $reportData['resolved_at'] = null; // Reset jika status kembali ke 'terbuka'
        }

        $report->update($reportData);
        return $report;
    }

    /**
     * Menghapus laporan kerusakan beserta gambarnya.
     */
    public function deleteReport(DamageReport $report): void
    {
        if ($report->image_damage) {
            Storage::disk('public')->delete($report->image_damage);
        }
        $report->delete();
    }

    /**
     * Menyiapkan data array untuk disimpan ke database.
     */
    private function prepareReportData(array $data, ?UploadedFile $image, ?string $existingImagePath = null): array
    {
        $reportData = [
            'location_id' => $data['selectedLocationId'],
            'reported_by' => $data['reported_by_name'],
            'description' => $data['description'],
            'severity' => $data['severity'],
            'status' => $data['status'],
        ];

        // Cari user ID berdasarkan nama pelapor
        $user = User::where('name', $data['reported_by_name'])->first();
        $reportData['reported_by_id_user'] = $user?->id;

        if ($image) {
            // Hapus gambar lama jika ada gambar baru
            if ($existingImagePath) {
                Storage::disk('public')->delete($existingImagePath);
            }
            $reportData['image_damage'] = $image->store('damage-reports', 'public');
        }

        return $reportData;
    }
}
