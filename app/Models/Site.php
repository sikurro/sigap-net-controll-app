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
        'work_scheme',
        'site_class',
        'total_weight',
        'total_maintenance_hours',
        'technical_staff_needed',
        'non_technical_staff_needed',
        'existing_technical_staff',
        'existing_non_technical_staff',
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
