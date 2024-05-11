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
     */
    public function __invoke()
    {
        //TODO : caching

        return IndustryResource::collection(Industry::all())
        ->response()
        ->withHeaders(['Accept' => 'application/json']); 
    }
}
