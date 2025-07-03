<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = ['user_id', 'plan_id', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}