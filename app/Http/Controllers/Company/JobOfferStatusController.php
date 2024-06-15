<?php

namespace App\Http\Controllers\Company;

use App\Models\Company;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use App\Constants\JobOfferStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\JobOfferResource;
use App\Http\Requests\Company\ChangeJobOfferStatusRequest;

class JobOfferStatusController extends Controller
{
    public function change(ChangeJobOfferStatusRequest $request)
    {
        $id = $request->validated()['job_offer_id'] ;
        $newStatus = $request->validated()['status'] ;

        $jobOffer =JobOffer::whereAll([
            'id'=> $id,
            'company_id'=>auth()->user()->role['id']
            ])->first() ;

        if($jobOffer == null){
            return response()->json([
                'message' => 'you don\'t have a job offer with id ' . $id
            ] , 404) ;
        }

        if($newStatus == JobOfferStatus::PENDING)
        {
            //todo test if this is working
            $jobOffer->freelancers()->detach() ;
        }
        $jobOffer->status = $newStatus ;
        $jobOffer->save() ;
    
        return JobOfferResource::make($jobOffer) ;
    }
}
