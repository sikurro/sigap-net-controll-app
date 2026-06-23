<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentCategoryBaseline extends Model
{
    use HasFactory;

    protected $table = 'equipment_category_baselines';

    protected $fillable = [
        'category',
        'baseline',
    ];
}
