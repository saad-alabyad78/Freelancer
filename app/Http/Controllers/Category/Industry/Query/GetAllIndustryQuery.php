<?php

namespace App\Http\Controllers\Category\Industry\Query;

use App\Models\Industry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category\IndustryResource;
/**
 * @group Category Managment
 * 
 **/
class GetAllIndustryQuery extends Controller
{
    public function __invoke()
    {
        return IndustryResource::collection(Industry::all()); 
    }
}
