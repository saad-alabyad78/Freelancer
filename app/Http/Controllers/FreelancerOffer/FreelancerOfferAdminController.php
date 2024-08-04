<?php

namespace App\Http\Controllers\FreelancerOffer;

use Illuminate\Support\Carbon;
use App\Models\FreelancerOffer;
use App\Http\Controllers\Controller;
use App\Constants\FreelancerOfferStatus;
use App\Http\Resources\FreelancerOffer\FreelancerOfferResource;
use App\Http\Requests\FreelancerOffer\FilterFreelancerOfferForAdminRequest;


/**
 * @group Freelancer Offer Managment
 */
class FreelancerOfferAdminController extends Controller
{
    public function __construct(){
        $this->middleware('role:admin');
    }
    /**
     * Admin-Filter List Freelancer Offers
     */
    public function adminFilter(FilterFreelancerOfferForAdminRequest $request)
    {
        $freelancerOffers = FreelancerOffer::filter($request->validated())
        ->with(['skills' , 'files' , 'sub_category'])
        ->orderByDesc('created_at')
        ->paginate(20) ;

        return FreelancerOfferResource::collection($freelancerOffers) ;
    }
    /**
     * Admin Accept
     */
    public function accept(FreelancerOffer $freelancerOffer)
    {
        if($freelancerOffer->status != FreelancerOfferStatus::PENDING)
        {
            return response()->json([
                'message'=>'the offer status is not pending' ,
            ] , 422);
        }

        $freelancerOffer->update([
            'status' => FreelancerOfferStatus::ACTIVE ,
            'posted_at' => Carbon::now() ,
            ]) ;

        //todo:send message to Freelancer
        
        return FreelancerOfferResource::make($freelancerOffer) ;
    }
    /**
     * Admin Reject
     */
    public function reject(FreelancerOffer $freelancerOffer)
    {
        if($freelancerOffer->status != FreelancerOfferStatus::PENDING)
        {
            return response()->json([
                'message'=>'the offer status is not pending' ,
            ] , 422) ;
        }

        //todo:send message to Freelancer

        $freelancerOffer->delete() ;

        return response()->json(['message'=>'deleted']) ;
    }
    /**
     * Admin Delete
     */
    public function delete(FreelancerOffer $freelancerOffer)
    {
        //todo:send message to Freelancer

        $freelancerOffer->delete() ;

        return response()->json(['message' => 'deleted']) ;
    }
}
