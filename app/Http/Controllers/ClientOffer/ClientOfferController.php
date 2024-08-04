<?php

namespace App\Http\Controllers\ClientOffer;

use App\Models\File;
use App\Models\ClientOffer;
use Illuminate\Http\Request;
use App\Models\ClientOfferProposal;
use App\Constants\ClientOfferStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientOffer\GetProposalsRequest;
use App\Http\Resources\ClientOffer\ClientOfferResource;
use App\Http\Requests\ClientOffer\CreateClientOfferRequest;
use App\Http\Requests\ClientOffer\FilterClientOfferRequest;
use App\Http\Requests\ClientOffer\UpdateClientOfferRequest;

use App\Http\Requests\ClientOffer\ClientAcceptProposalsRequest;
use App\Http\Requests\ClientOffer\ClientRejectProposalsRequest;
use App\Http\Resources\ClientOffer\ClientOfferProposalResource;
/**
 * @group Client Offer Management
 * 
 */
class ClientOfferController extends Controller
{
    public function __construct(){
        $this->middleware('role:client');
    }

    /**
     * Accept Proposal
     */
    
    public function acceptProposal(ClientAcceptProposalsRequest $request)
    {
        $proposal = ClientOfferProposal::where('id' , $request->input('proposal_id'))
        ->first() ;

        $proposal->update(['accepted_at' => now()->toDateTimeString()]) ;

        ClientOfferProposal::where('client_offer_id' , $proposal->client_offer_id)
        ->whereNot('id' , $proposal->id)
        ->update(['rejected_at' => now()->toDateTimeString()]) ;

        $offer = ClientOffer::where('id' , $proposal->client_offer_id)->first() ;
        $offer->update(['status' => ClientOfferStatus::IN_PROGRESS]) ;

        $offer->update(['freelancer_id' => $proposal->freelancer_id]) ;

        //todo send notification to the freelancer 
        
        return ClientOfferResource::make($offer->load([
            'freelancer',
            'client',
            'client_offer',
            'sub_category',
            'files',
            'skills',
        ]))  ;
    }
    //todo test 
    /**
     * Reject Proposals
     * @param \App\Http\Requests\ClientOffer\ClientRejectProposalsRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function rejectProposals(ClientRejectProposalsRequest $request)
    {
        $proposals = ClientOfferProposal::whereIn('id' , $request->input('proposal_ids'))
        ->update(['rejected_at' => now()->toDateTimeString()]) ;

        //todo send api to the freelancer 
        return ClientOfferProposalResource::collection($proposals) ;
    }

    //todo test
    /**
     * List of proposals
     * @param \App\Http\Requests\ClientOffer\GetProposalsRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function proposals(GetProposalsRequest $request)
    {
        $proposals = ClientOfferProposal
        ::where('client_offer_id' , $request->input('client_offer_id'))
        ->orderBy('days' , $request->boolean('orderByDays')? 'asc' : 'desc')
        ->orderBy('price' , $request->boolean('orderByPrice')? 'asc' : 'desc')
        ->paginate() ;

        return ClientOfferProposalResource::collection(
            $proposals->load(['freelancer.job_role'])
        ) ;
    }
    /**
     *  Client-Filter List Client Offers
     * @param \App\Http\Requests\ClientOffer\FilterClientOfferRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function clientFilter(FilterClientOfferRequest $request)
    {
        $clientOffers = ClientOffer::filter($request->validated())
        ->where('client_id' , auth('sanctum')->user()->role_id)
        ->with(['skills' , 'sub_category'])
        ->orderByDesc('created_at')
        ->paginate(20) ;

        return ClientOfferResource::collection($clientOffers) ;
    }
    /**
     * Client Store Offer
     * 
     * @param \App\Http\Requests\ClientOffer\CreateClientOfferRequest $request
     * @return ClientOfferResource
     */
    public function store(CreateClientOfferRequest $request)
    {
        $data = $request->validated() ;
        $data['client_id'] = auth('sanctum')->user()->role_id ;
        $data['status'] = ClientOfferStatus::PENDING ;

        $clientOffer = ClientOffer::create($data) ;

        $clientOffer->skills()->attach($data['skill_ids']) ;
        
        if($data['file_ids']??false)
        {
           File::whereIn('id' , $data['file_ids'])
            ->update([
                'filable_id' => $clientOffer->id ,
                'filable_type' => ClientOffer::class ,
            ]) ; 
        }

        return ClientOfferResource::make($clientOffer->load([
            'files' ,
            'sub_category' ,
            'skills' ,
            ])) ;
    }

    /**
     * Client Show Offers
     * 
     * @param \App\Models\ClientOffer $clientOffer
     * @return ClientOfferResource|mixed|\Illuminate\Http\JsonResponse
     */
    public function show(ClientOffer $clientOffer)
    {
        //$this->authorize('view' , $clientOffer) ;

        if($clientOffer->client_id != auth('sanctum')->user()->role_id)
        {
            return response()->json([
                'error' => 'unauthorized' ,
            ] , 403);
        }
        
        return ClientOfferResource::make($clientOffer->load([
            'files' ,
            'sub_category' ,
            'skills' ,
            ])) ;
    }

    /**
     * Client Update Pending Offers
     * 
     * @param \App\Http\Requests\ClientOffer\UpdateClientOfferRequest $request
     * @return ClientOfferResource|mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateClientOfferRequest $request)
    {   
        $data = $request->validated() ;
        
        $clientOffer = ClientOffer::findOrFail($data['client_offer_id']) ;

        if($clientOffer->client_id != auth('sanctum')->user()->role_id)
        {
            return response()->json([
                'error' => 'unauthorized' ,
            ] , 403);
        }

        //$this->authorize('update' , $clientOffer) ;
    
        if($clientOffer->status != ClientOfferStatus::PENDING)
        {
            return response()->json(
                ['error' => 'client offer status is not pending']) ;
        }

        $clientOffer->update($data) ;

        $clientOffer->skills()->sync($data['skill_ids']) ;
        
        //delete old files 
        $clientOffer
        ->files()
        ->whereNotIn('id' , $data['file_ids'])
        ->update(['deleted' => true]) ;
        
        //add old files
        File::whereNull('filable_id')
            ->whereNull('filable_type') 
            ->whereIn('id' , $data['file_ids'])
            ->update([
                'filable_id' => $clientOffer->id ,
                'filable_type' => ClientOffer::class ,
            ]);

        return ClientOfferResource::make($clientOffer->load([
            'files' ,
            'sub_category' ,
            'skills' ,
            ])) ;
    }

    /**
     * Client Delete Offers
     * 
     * @param \App\Models\ClientOffer $clientOffer
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(ClientOffer $clientOffer)
    {
        //$this->authorize('delete' , $clientOffer) ;

        if($clientOffer->client_id != auth('sanctum')->user()->role_id)
        {
            return response()->json([
                'error' => 'unauthorized' ,
            ] , 403);
        }
        
        $clientOffer->delete() ;

        return response()->json(['massage'=>'deleted']) ;
    }
}