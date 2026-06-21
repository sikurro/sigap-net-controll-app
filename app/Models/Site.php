<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'region',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function equipments()
    {
        return $this->hasMany(SiteEquipment::class);
    }

    public function getRequiredTechniciansAttribute()
    {
        return 0; // Placeholder for Milestone 4
    }

    public function getRequiredNonTechniciansAttribute()
    {
        return 0; // Placeholder for Milestone 4
    }
}
