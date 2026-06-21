<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NonTechnicalPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
    ];

    public function siteClasses(): BelongsToMany
    {
        return $this->belongsToMany(SiteClass::class, 'non_technical_requirements')
            ->withPivot('id', 'quantity')
            ->withTimestamps();
    }
}
