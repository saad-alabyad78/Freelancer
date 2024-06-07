<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use App\Interfaces\IClientRepository;
use App\Interfaces\ICompanyRepository;
use App\Repositories\ClientRepository;
use App\Repositories\CompanyRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\IPortfolioRepository;
use App\Interfaces\IFreelancerRepository;
use App\Repositories\PortfolioRepository;
use App\Repositories\FreelancerRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IClientRepository::class , ClientRepository::class) ;
        $this->app->bind(ICompanyRepository::class , CompanyRepository::class) ;
        $this->app->bind(IFreelancerRepository::class , FreelancerRepository::class) ;
        $this->app->bind(IPortfolioRepository::class , PortfolioRepository::class) ;
    }

    /**
     * Bootstrap any application services.
     *
     * @param UrlGenerator $url
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }
    }
}
