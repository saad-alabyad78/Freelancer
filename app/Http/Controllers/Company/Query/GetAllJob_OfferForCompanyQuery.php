<?php

namespace App\Http\Controllers\Company\Query;

use App\Models\Job_Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\Job_OfferResource;

class GetAllJob_OfferQueryForCompany extends Controller
{
    public function __invoke()
    {
        //TODO: filter scope
        //TODO: with 
        //TODO: return for the company only
    }
}
