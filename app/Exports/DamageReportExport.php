<?php

namespace App\Exports;

use App\Models\DamageReport;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DamageReportExport implements FromQuery, WithHeadings, WithMapping
{
    protected string $search;
    protected string $status;
    protected string $location;
    protected string $severity;

    /**
     * Menerima nilai filter dari komponen Livewire.
     */
    public function __construct(string $search, string $status, string $location, string $severity)
    {
        $this->search = $search;
        $this->status = $status;
        $this->location = $location;
        $this->severity = $severity;
    }

    /**
     * Mendefinisikan query untuk mengambil data dari database.
     * Query ini menghormati filter yang sedang aktif.
     */
    public function query(): Builder
    {
        return DamageReport::query()
            ->with(['location:id,name', 'userReportedBy:id,name']) // Eager load untuk efisiensi
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                    ->orWhere('reported_by', 'like', '%' . $this->search . '%')
                    ->orWhereHas('location', fn($sq) => $sq->where('name', 'like', '%' . $this->search . '%'));
            })
            ->when($this->status, fn($query) => $query->where('status', $this->status))
            ->when($this->location, fn($query) => $query->where('location_id', $this->location))
            ->when($this->severity, fn($query) => $query->where('severity', $this->severity))
            ->latest('reported_at');
    }

    /**
     * Mendefinisikan baris header untuk file CSV.
     */
    public function headings(): array
    {
        return [
            'ID Laporan',
            'Lokasi',
            'Pelapor',
            'Deskripsi Kerusakan',
            'Tipe Kerusakan',
            'Status',
            'Tanggal Dilaporkan',
            'Tanggal Diselesaikan',
        ];
    }

    /**
     * Memetakan setiap baris data ke format yang diinginkan.
     * @param DamageReport $report
     */
    public function map($report): array
    {
        return [
            $report->id,
            $report->location->name ?? 'N/A',
            $report->userReportedBy->name ?? $report->reported_by,
            $report->description,
            ucfirst($report->severity),
            ucfirst(str_replace('_', ' ', $report->status)),
            $report->reported_at->format('d-m-Y H:i'),
            $report->resolved_at ? $report->resolved_at->format('d-m-Y H:i') : 'Belum Selesai',
        ];
    }
}
