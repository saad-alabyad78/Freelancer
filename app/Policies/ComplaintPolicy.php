<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    /**
     * Determine whether the user can view any complaints.
     */
    public function viewAny(User $user)
    {
        return $user->role_name == 'admin';
    }

    /**
     * Determine whether the user can create a complaint.
     */
    public function create(User $user)
    {
        return $user->id !== null;
    }

    /**
     * Determine whether the user can freeze another user.
     */
    public function freezeUser(User $user)
    {
        return $user->role_name == 'admin';
    }
}

