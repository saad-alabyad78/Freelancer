<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\ClientOffer;
use App\Models\FreelancerOffer;
use App\Models\JobOfferProposal;
use App\Policies\ClientOfferPolicy;
use App\Policies\FreelancerOfferPolicy;
use App\Policies\JobOfferProposalPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Invitation::class => InvitationPolicy::class,
        ClientOffer::class => ClientOfferPolicy::class ,
        FreelancerOffer::class => FreelancerOfferPolicy::class ,
        JobOfferProposal::class => JobOfferProposalPolicy::class ,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
