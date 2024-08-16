<?php

namespace App\Policies;

use App\Models\MoneyTransfer;
use App\Models\User;

class MoneyTransferPolicy
{
    /**
     * Determine whether the user can create a money transfer.
     */
    public function create(User $user)
    {
        return $user->role_name == 'admin';
    }
}
