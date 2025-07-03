<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    protected $fillable = [
        'brand_id',
        'domain_zone_id',
        'domain',
        'is_available',
        'affiliate_link',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function domainZone(): BelongsTo
    {
        return $this->belongsTo(DomainZone::class);
    }
}