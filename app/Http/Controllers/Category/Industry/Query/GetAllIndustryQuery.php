<?php

namespace App\Http\Controllers\Category\Industry\Query;

use App\Models\Industry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Category\IndustryResource;
/**
 * @group Category Managment
 * 
 **/
class GetAllIndustryQuery extends Controller
{
    /**
     * get all industries
     * 
     * @apiResourceCollecton App\Http\Resources\Category\IndustryResource
     * @apiResourceModel App\Models\Industry
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        //TODO : caching

        $industries = Cache::rememberForever('key' , function(){
            return Industry::all();
        });

        return IndustryResource::collection($industries)
        ->response()
        ->withHeaders(['Accept' => 'application/json']); 
    }
}
