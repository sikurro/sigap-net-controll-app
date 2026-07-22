<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteEquipment extends Model
{
    use HasFactory;

    protected $table = 'site_equipments';

    protected $fillable = [
        'site_id',
        'equipment_type_id',
        'quantity',
        'utilization_rate',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'utilization_rate' => 'float',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }
}
