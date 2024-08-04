<?php

namespace App\Http\Controllers\FreelancerOffer;

use App\Models\File;
use App\Models\FreelancerOffer;
use App\Http\Controllers\Controller;
use App\Constants\FreelancerOfferStatus;
use App\Http\Resources\FreelancerOffer\FreelancerOfferResource;
use App\Http\Requests\FreelancerOffer\CreateFreelancerOfferRequest;
use App\Http\Requests\FreelancerOffer\FilterFreelancerOfferRequest;
use App\Http\Requests\FreelancerOffer\UpdateFreelancerOfferRequest;


/**
 * @group Freelancer Offer Management
 * 
 */
class FreelancerOfferController extends Controller
{
    public function __construct(){
        $this->middleware('role:freelancer');
    }
    /**
     *  Freelancer-Filter List Freelancer Offers
     * @param FilterFreelancerOfferRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function FreelancerFilter(FilterFreelancerOfferRequest $request)
    {
        $freelancerOffers = FreelancerOffer::filter($request->validated())
        ->where('freelancer_id' , auth('sanctum')->user()->role_id)
        ->with(['skills' , 'sub_category'])
        ->orderByDesc('created_at')
        ->paginate(20) ;

        return FreelancerOfferResource::collection($freelancerOffers) ;
    }
    /**
     * Freelancer Store Offer
     * 
     * @param \App\Http\Requests\FreelancerOffer\CreateFreelancerOfferRequest $request
     * @return FreelancerOfferResource
     */
    public function store(CreateFreelancerOfferRequest $request)
    {
        
        $data = $request->validated() ;
        $data['freelancer_id'] = auth('sanctum')->user()->role_id ;
        $data['status'] = FreelancerOfferStatus::PENDING ;

        $freelancerOffer = FreelancerOffer::create($data) ;

        $freelancerOffer->skills()->attach($data['skill_ids']) ;
        
        if($data['file_ids']??false)
        {
           File::whereIn('id' , $data['file_ids'])
            ->update([
                'filable_id' => $freelancerOffer->id ,
                'filable_type' => FreelancerOffer::class ,
            ]) ; 
        }

        return FreelancerOfferResource::make($freelancerOffer->load([
            'files' ,
            'sub_category' ,
            'skills' ,
            ])) ;
    }

    /**
     * Freelancer Show Offers
     * 
     * @param \App\Models\FreelancerOffer $freelancerOffer
     * @return FreelancerOfferResource|mixed|\Illuminate\Http\JsonResponse
     */
    public function show(FreelancerOffer $freelancerOffer)
    {
        //$this->authorize('view' , $freelancerOffer) ;

        if($freelancerOffer->freelancer_id != auth('sanctum')->user()->role_id)
        {
            return response()->json([
                'error' => 'unauthorized' ,
            ] , 403);
        }
        
        return FreelancerOfferResource::make($freelancerOffer->load([
            'files' ,
            'sub_category' ,
            'skills' ,
            ])) ;
    }

    /**
     * Freelancer Update Pending Offers
     * 
     * @param \App\Http\Requests\FreelancerOffer\UpdateFreelancerOfferRequest $request
     * @return FreelancerOfferResource|mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateFreelancerOfferRequest $request)
    {   
        $data = $request->validated() ;
        
        $freelancerOffer = FreelancerOffer::findOrFail($data['freelancer_offer_id']) ;

        if($freelancerOffer->freelancer_id != auth('sanctum')->user()->role_id)
        {
            return response()->json([
                'error' => 'unauthorized' ,
            ] , 403);
        }

        //$this->authorize('update' , $freelancerOffer) ;
    
        if($freelancerOffer->status != FreelancerOfferStatus::PENDING)
        {
            return response()->json(
                ['error' => 'Freelancer offer status is not pending']) ;
        }

        $freelancerOffer->update($data) ;

        $freelancerOffer->skills()->sync($data['skill_ids']) ;
        
        //delete old files 
        $freelancerOffer
        ->files()
        ->whereNotIn('id' , $data['file_ids'])
        ->update(['deleted' => true]) ;
        
        //add old files
        File::whereNull('filable_id')
            ->whereNull('filable_type') 
            ->whereIn('id' , $data['file_ids'])
            ->update([
                'filable_id' => $freelancerOffer->id ,
                'filable_type' => FreelancerOffer::class ,
            ]);

        return FreelancerOfferResource::make($freelancerOffer->load([
            'files' ,
            'sub_category' ,
            'skills' ,
            ])) ;
    }

    /**
     * Freelancer Delete Offers
     * 
     * @param \App\Models\FreelancerOffer $freelancerOffer
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(FreelancerOffer $freelancerOffer)
    {
        //$this->authorize('delete' , $freelancerOffer) ;

        if($freelancerOffer->freelancer_id != auth('sanctum')->user()->role_id)
        {
            return response()->json([
                'error' => 'unauthorized' ,
            ] , 403);
        }
        
        $freelancerOffer->delete() ;

        return response()->json(['massage'=>'deleted']) ;
    }
}