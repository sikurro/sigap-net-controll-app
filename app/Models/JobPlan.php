<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_type_id',
        'type',
        'activity_name',
        'duration_minutes',
        'frequency_per_year',
        'total_hours_per_year',
    ];

    protected $appends = [
        'duration_hours',
    ];

    public function getDurationHoursAttribute()
    {
        return round($this->duration_minutes / 60, 3);
    }

    public function equipmentType(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class);
    }
}
