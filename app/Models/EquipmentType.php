<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'weight',
        'category_id',
    ];

    public function jobPlans(): HasMany
    {
        return $this->hasMany(JobPlan::class);
    }

    public function categoryBaseline(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(EquipmentCategoryBaseline::class, 'category_id');
    }
}
