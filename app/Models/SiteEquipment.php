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
        'status',
        'quantity',
    ];

    protected $casts = [
        'status' => 'boolean',
        'quantity' => 'integer',
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
