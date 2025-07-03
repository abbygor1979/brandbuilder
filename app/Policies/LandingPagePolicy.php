<?php

namespace App\Policies;

use App\Models\LandingPage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LandingPagePolicy
{
    public function view(User $user, LandingPage $landingPage): Response
    {
        return $user->id === $landingPage->brand->user_id || $user->is_admin
            ? Response::allow()
            : Response::deny('You do not own this landing page.');
    }
}