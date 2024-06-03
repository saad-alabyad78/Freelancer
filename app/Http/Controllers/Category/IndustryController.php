<?php

namespace App\Http\Controllers\Category;

use App\Models\Industry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category\IndustryResource;
use App\Http\Requests\Category\IndustrySearchRequest;
/**
 * @group Category Managment
 * 
 **/
class IndustryController extends Controller
{
    /**
     * search industry
     * get first 100 match
     * 
     * @apiResourceCollecton App\Http\Resources\Category\IndustryResource
     * @apiResourceModel App\Models\Industry
     * 
     */
    public function search(IndustrySearchRequest $request)
    {
        $name = $request->input('name') ;
        //TO DO:search scout and cache

        $industries = Industry::where('name' , 'like' , '%' . $name . '%')->limit(100)->get() ;

        return IndustryResource::collection($industries) ;
    }
}
