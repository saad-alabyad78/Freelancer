<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
{
    use HandlesAuthorization;

    public function sendInvitation(User $user)
    {
        return $user->role === 'company';
    }
    public function deleteInvitation(User $user, Invitation $invitation)
    {
        return $user->id === $invitation->company_id;
    }

    public function respondToInvitation(User $user, Invitation $invitation)
    {
        return $user->id === $invitation->freelancer_id;
    }
}

