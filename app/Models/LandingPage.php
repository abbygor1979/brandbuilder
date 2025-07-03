<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LandingPage extends Model
{
    protected $fillable = [
        'brand_id',
        'zip_path',
        'deploy_status',
        'deploy_url',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(Component::class)->orderBy('order');
    }
}