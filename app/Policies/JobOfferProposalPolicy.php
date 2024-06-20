<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;
use App\Models\Freelancer;
use App\Models\JobOfferProposal;
use Illuminate\Auth\Access\Response;

class JobOfferProposalPolicy
{
    public function reject(User $user, JobOfferProposal $jobOfferProposal): bool
    {
        return $user->role_type == Company::class
            && $user->role_id == $jobOfferProposal->job_offer->company_id;
    }

    public function accept(User $user, JobOfferProposal $jobOfferProposal): bool
    {
        return $user->role_type == Company::class
            && $user->role_id == $jobOfferProposal->job_offer->company_id;
    }
    public function filter(User $user)
    {
        //todo only company
        return $user->role_type == Company::class;
    }

    public function view(User $user, JobOfferProposal $jobOfferProposal): bool
    {
        if ($user->role_type == Freelancer::class
            && $user->role_id == $jobOfferProposal->freelancer_id
        ) {
            return true;
        }

        if ($user->role_type == Company::class
            && $user->role_id == $jobOfferProposal->job_offer->company_id
        ) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role_type == Freelancer::class;
    }

    public function update(User $user, JobOfferProposal $jobOfferProposal): bool
    {
        return $user->role_type == Freelancer::class
            && $jobOfferProposal->freelancer_id == $user->role_id;
    }

    public function delete(User $user, JobOfferProposal $jobOfferProposal): bool
    {
        return $user->role_type == Freelancer::class
            && $jobOfferProposal->freelancer_id == $user->role_id;
    }
}
