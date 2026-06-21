<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NonTechnicalRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_class_id',
        'non_technical_position_id',
        'quantity',
    ];

    public function siteClass(): BelongsTo
    {
        return $this->belongsTo(SiteClass::class);
    }

    public function nonTechnicalPosition(): BelongsTo
    {
        return $this->belongsTo(NonTechnicalPosition::class);
    }
}
