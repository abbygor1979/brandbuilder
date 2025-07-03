<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DomainZone extends Model
{
    protected $fillable = ['zone', 'is_popular'];

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }
}