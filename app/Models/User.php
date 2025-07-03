<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Notifiable, Billable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ]; */
	
	protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
			'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the brands owned by the user.
     */
    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    /**
     * Get the user's subscription.
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }
}