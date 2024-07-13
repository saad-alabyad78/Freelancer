<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClientOffer;
use App\Models\FreelancerOffer;
use Illuminate\Auth\Access\Response;

class FreelancerOfferPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FreelancerOffer $freelancerOffer): bool
    {
        return auth('sanctum')->check() and $freelancerOffer->freelancer_id == $user->role_id ;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FreelancerOffer $freelancerOffer): bool
    {
        return auth('sanctum')->check() and $freelancerOffer->freelancer_id == $user->role_id ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FreelancerOffer $freelancerOffer): bool
    {
        return auth('sanctum')->check() and $freelancerOffer->freelancer_id == $user->role_id ;
    }
}
