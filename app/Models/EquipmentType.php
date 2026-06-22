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
        'category',
    ];

    public function jobPlans(): HasMany
    {
        return $this->hasMany(JobPlan::class);
    }
}
