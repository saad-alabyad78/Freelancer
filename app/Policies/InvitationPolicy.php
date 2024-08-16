<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invitation;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can send an invitation.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function sendInvitation(User $user)
    {
        return $user->role_name === 'company';
    }

    /**
     * Determine whether the user can delete the invitation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invitation  $invitation
     * @return bool
     */
    public function deleteInvitation(User $user, Invitation $invitation)
    {
        return $user->id === $invitation->company_id;
    }

    /**
     * Determine whether the user can respond to the invitation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invitation  $invitation
     * @return bool
     */
    public function respondToInvitation(User $user, Invitation $invitation)
    {
        // Ensure the invitation is not already accepted or rejected
        if ($invitation->accepted_at || $invitation->rejected_at) {
            return false;
        }

        return $user->id === $invitation->freelancer_id;
    }
}
