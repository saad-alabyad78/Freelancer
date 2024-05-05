<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Models\Industry;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CreateJob_OfferRequest;

class CreateJob_Offer extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Company $company ,Industry $industry , CreateJob_OfferRequest $request)
    {
        return [
            $request->validated() ,
            $company ,
            $industry
        ] ;

        //status = pending 
    }
}
