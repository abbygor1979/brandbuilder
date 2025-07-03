<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BrandPolicy
{
    public function view(User $user, Brand $brand): Response
    {
        return $user->id === $brand->user_id || $user->is_admin
            ? Response::allow()
            : Response::deny('You do not own this brand.');
    }
}