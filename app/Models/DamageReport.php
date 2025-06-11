<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamageReport extends Model
{
    use HasFactory;

    public const SEVERITY_RINGAN = 'ringan';
    public const SEVERITY_SEDANG = 'sedang';
    public const SEVERITY_BERAT = 'berat';

    public static $allowedSeverities = [
        self::SEVERITY_RINGAN,
        self::SEVERITY_SEDANG,
        self::SEVERITY_BERAT,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'location_id',
        'reported_by_id_user',
        'reported_by',
        'description',
        'severity',
        'status',
        'image_damage',
        'reported_at',
        'resolved_at',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by_id_user');
    }

    public function userReportedBy() // Atau reporterUser(), atau user()
    {
        return $this->belongsTo(User::class, 'reported_by_id_user');
    }
}
