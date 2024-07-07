<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClientOffer;
use Illuminate\Auth\Access\Response;

class ClientOfferPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClientOffer $clientOffer): bool
    {
        return auth('sanctum')->check() and $clientOffer->client_id == $user->role_id ;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClientOffer $clientOffer): bool
    {
        return auth('sanctum')->check() and $clientOffer->client_id == $user->role_id ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClientOffer $clientOffer): bool
    {
        return auth('sanctum')->check() and $clientOffer->client_id == $user->role_id ;
    }
}
