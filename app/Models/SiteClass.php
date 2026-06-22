<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SiteClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_weight',
        'max_weight',
    ];

    public function nonTechnicalPositions(): BelongsToMany
    {
        return $this->belongsToMany(NonTechnicalPosition::class, 'non_technical_requirements')
            ->withPivot('id', 'quantity')
            ->withTimestamps();
    }
}
