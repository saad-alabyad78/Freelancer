<?php

namespace App\Providers;

use App\Models\File;
use App\Models\Image;
use App\Models\Client;
use App\Models\Company;
use App\Models\JobOffer;
use App\Models\Portfolio;
use App\Models\Freelancer;
use App\Models\GalleryImage;
use App\Observers\FileObserver;
use App\Observers\ImageObserver;
use App\Observers\ClientObserver;
use App\Observers\CompanyObserver;
use App\Observers\JobOfferObserver;
use App\Observers\PortfolioObserver;
use App\Observers\FreelancerObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Observers\GalleryImageObserver;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $observers = [
        Company::class => [
            CompanyObserver::class ,
        ],
        JobOffer::class => [
            JobOfferObserver::class ,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
