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
        'activity_name',
        'duration_hours',
        'frequency_per_year',
        'total_hours_per_year',
    ];

    public function equipmentType(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class);
    }
}
