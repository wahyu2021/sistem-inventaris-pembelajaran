<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{   
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'description',
        'image',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function damageReport(): HasMany
    {
        return $this->hasMany(DamageReport::class);
    }
}
