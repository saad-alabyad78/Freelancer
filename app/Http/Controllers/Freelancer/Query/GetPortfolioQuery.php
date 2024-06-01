<?php

namespace App\Http\Controllers\Freelancer\Query;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Freelancer\PortfolioResource;

/**
 *@group Freelancer Managment 
 **/
class GetPortfolioQuery extends Controller
{
    /**
     * Get Portfolio .
     * 
     * 
     * @apiResource App\Http\Resources\Freelancer\PortfolioResource with=App\Http\Resources\Category\SkillResource
     * @apiResourceModel App\Models\Portfolio with=App\Models\Skill,App\Models\File,App\Models\Image
     * 
     * 
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     * 
     */
    public function __invoke(Portfolio $portfolio)
    {
        return PortfolioResource::make($portfolio->load(['skills' , 'files' , 'images']))
            ->response()
            ->withHeaders(['Content-Type' => 'application/json']) ;
    }
}
