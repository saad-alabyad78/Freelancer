<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\DeleteJobOfferRequest;

/**
 * @group Company Managment
 * 
 */
class DeleteJob_OfferCommand extends Controller
{
    /**
     * Update Job Offer .
     * 
     * @authenticated
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function __invoke(DeleteJobOfferRequest $request)
    {
        $data = $request->validated() ;

        //chick wither this is his job offer or not 

        $job_offer = JobOffer::findOrFail($data['job_offer_id']);

        if($job_offer->company_id != auth()->user()->role['id']){
            return response()->json([
                'this is not your job offer !' , 
                422
            ]);
        }

        $job_offer->delete() ;

        return response('deleted') ;
    }
}
