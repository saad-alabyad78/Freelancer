<?php

namespace App\Http\Controllers\FreelancerOffer;

use App\Models\FreelancerOffer;
use App\Http\Controllers\Controller;
use App\Models\FreelancerOfferProposal;
use App\Constants\FreelancerOfferStatus;
use App\Http\Resources\FreelancerOffer\FreelancerOfferResource;
use App\Http\Resources\FreelancerOffer\FreelancerOfferProposalResource;
use App\Http\Requests\FreelancerOffer\CreateFreelancerOfferProposalRequest;
use App\Http\Requests\FreelancerOffer\UpdateFreelancerOfferProposalRequest;
use App\Http\Requests\FreelancerOffer\FilterFreelancerOfferForClientRequest;



/**
 * @group Freelancer Offer Managment
 * 
 */
class FreelancerOfferClientController extends Controller
{
    public function __construct(){
        $this->middleware('role:client');
    }

    /**
     * 
     * Client Filter
     * 
     * @param \App\Http\Requests\FreelancerOffer\FilterFreelancerOfferForClientRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function clientFilter(FilterFreelancerOfferForClientRequest $request)
    {
        $freelancerOffers = FreelancerOffer::whereNot('status' , FreelancerOfferStatus::PENDING)
        ->filter($request->validated())
        ->with(['skills' , 'sub_category'])
        ->orderByDesc('created_at')
        ->paginate(20) ;

        return FreelancerOfferResource::collection($freelancerOffers) ;
    }
    /**
     * Client Show Offer
     * @param \App\Models\ClientOffer $clientOffer
     * @return FreelancerOfferResource|mixed|\Illuminate\Http\JsonResponse
     */
    public function showFreelancerOffer(FreelancerOffer $freelancerOffer)
    {
        if($freelancerOffer->status == FreelancerOfferStatus::PENDING)
        {
            return response()->json(['error' => 'you can\'t see pending offers'],403) ;
        }
        return FreelancerOfferResource::make($freelancerOffer) ;
    }

    /**
     * 
     * Client Propose
     * 
     * @param \App\Http\Requests\FreelancerOffer\CreateFreelancerOfferProposalRequest $request
     * @return \App\Http\Resources\FreelancerOffer\FreelancerOfferProposalResource
     */
    public function createProposal(CreateFreelancerOfferProposalRequest $request)
    {
        $data = $request->validated() ;
        $data['client_id'] = auth('sanctum')->user()->role_id ;
        $freelancerOffer = FreelancerOffer::findOrFail($data['freelancer_offer_id'])->first() ;
        $data['freelancer_id'] = $freelancerOffer->freelancer_id ;
        
        $proposal = FreelancerOfferProposal::create($data) ;
        
        $freelancerOffer->increment('proposals_count') ;

        return FreelancerOfferProposalResource::make($proposal) ;
    }

    /**
     * 
     * Client Update Proposal Message
     * 
     * @param \App\Http\Requests\FreelancerOffer\UpdateFreelancerOfferProposalRequest $request
     * @return FreelancerOfferProposalResource
     */
    public function updateProposal(UpdateFreelancerOfferProposalRequest $request)
    {
        $data = $request->validated() ;

        $proposal = FreelancerOfferProposal::where('id' , $data['freelancer_offer_proposal_id'])->first() ;

        $proposal->update(['message' => $data['message']]) ;

        return FreelancerOfferProposalResource::make($proposal) ;
    }
    /**
     * Client Delete Proposal
     * 
     * @param \App\Models\FreelancerOfferProposal $freelancerOfferProposal
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function deleteProposal(FreelancerOfferProposal $freelancerOfferProposal)
    {
        if($freelancerOfferProposal->client_id != auth('sanctum')->user()->role_id)
        {
            return response()->json(['error'=>'this is not your proposal'],403) ;
        }

        FreelancerOffer::where('id' , $freelancerOfferProposal->freelancer_offer_id)->first()->decrement('proposals_count') ;
        
        $freelancerOfferProposal->delete() ;
        
        return response()->json(['message' => 'deleted']) ;
    }
}
