<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [
        'user_id',
        'keywords',
        'name',
        'slogan',
        'motto',
        'name_description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function niches(): BelongsToMany
    {
        return $this->belongsToMany(Niche::class, 'brand_niches');
    }

    public function logos(): HasMany
    {
        return $this->hasMany(Logo::class);
    }

    public function landingPages(): HasMany
    {
        return $this->hasMany(LandingPage::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }
}