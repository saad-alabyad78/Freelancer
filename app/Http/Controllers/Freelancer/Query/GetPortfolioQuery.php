<?php

namespace App\Http\Controllers\Freelancer\Query;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Freelancer\PortfolioResource;

class GetPortfolioQuery extends Controller
{
    
    public function __invoke(Portfolio $portfolio)
    {
        return PortfolioResource::make($portfolio->load(['skills' , 'files']))
            ->response()
            ->withHeaders(['Content-Type' => 'application/json']) ;
    }
}
