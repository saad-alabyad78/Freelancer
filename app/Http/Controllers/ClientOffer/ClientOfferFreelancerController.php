<?php

namespace App\Http\Controllers\ClientOffer;

use App\Models\Bill;
use App\Models\User;
use App\Models\Project;
use App\Models\ClientOffer;
use Illuminate\Http\Request;
use App\Models\ClientOfferProposal;
use App\Constants\ClientOfferStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\BillResource;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\ClientOffer\ClientOfferResource;
use App\Http\Resources\ClientOffer\ClientOfferProposalResource;
use App\Http\Requests\ClientOffer\CreateClientOfferProposalRequest;
use App\Http\Requests\ClientOffer\UpdateClientOfferProposalRequest;
use App\Http\Requests\ClientOffer\FilterClientOfferForFreelancerRequest;

/**
 * @group Client Offer Management
 * 
 */
class ClientOfferFreelancerController extends Controller
{
    public function __construct(){
        $this->middleware('role:freelancer');
    }

    /**
     * my-proposal (freelancer)
     * 
     * send query param ?without_rejected_at=1 to remove rejected proposals
     * send query param ?without_accepted_at=1 to remove accepted proposals
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function myProposals()
    {
        $user = User::where('id' , auth('sanctum')->id())->first() ;
                
        $proposals = ClientOfferProposal::where('freelancer_id' , $user->role_id)
        ->orderByDesc('created_at') ;

        if(request()->boolean('without_rejected_at')){
        
            $proposals->whereNull('rejected_at') ;
        }

        if(request()->boolean('without_accepted_at')){
            $proposals->whereNull('accepted_at') ;
        }
        
        return ClientOfferProposalResource::collection($proposals->paginate()) ;
    }

    /**
     * 
     * Freelancer Filter
     * 
     * @param \App\Http\Requests\ClientOffer\FilterClientOfferForFreelancerRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function freelancerFilter(FilterClientOfferForFreelancerRequest $request)
    {
        $clientOffers = ClientOffer::filter($request->validated())
        ->with(['skills' , 'sub_category'])
        ->where('status' , '<>' , ClientOfferStatus::PENDING)
        ->orderByDesc('created_at')
        ->paginate(20) ;

        return ClientOfferResource::collection($clientOffers) ;
    }
    /**
     * Freelance Show Offer
     * @param \App\Models\ClientOffer $clientOffer
     * @return ClientOfferResource|mixed|\Illuminate\Http\JsonResponse
     */
    public function showClientOffer(ClientOffer $clientOffer)
    {
        if($clientOffer->status == ClientOfferStatus::PENDING)
        {
            return response()->json(['error' => 'you can\'t see pending offers'],403) ;
        }
        if (!$clientOffer->freelancer_id) {
            return ClientOfferResource::make($clientOffer->load([
                'files',
                'sub_category',
                'skills',
            ]));
        }

        $project = Project::where('client_offer_id', $clientOffer->id)
            ->with(['freelancer', 'client'])
            ->first();

        $bills = Bill::where([
            'from_id' => $clientOffer->client_id,
            'from_type' => 'clients',
            'to_id' => $project->id,
            'to_type' => 'projects',
        ])
        ->first();

        return ClientOfferResource::make($clientOffer->load([
            'freelancer',
            'client',
            'sub_category',
            'files',
            'skills']))->additional(
                [
                    'bills' => BillResource::collection($bills),

                    'project' => ProjectResource::make($project->load([
                        'freelancer',
                        'client',
                    ])),
                ]
            );
    }

    /**
     * 
     * Freelancer Propose
     * 
     * @param \App\Http\Requests\ClientOffer\CreateClientOfferProposalRequest $request
     * @return ClientOfferProposalResource
     */
    public function createProposal(CreateClientOfferProposalRequest $request)
    {
        $freelancerId = auth('sanctum')->user()->role_id ;
        
        $oldProposal = ClientOfferProposal::where('freelancer_id' , $freelancerId)
        ->where('client_offer_id' , $request->input('client_offer_id'))
        ->whereNull('rejected_at') ;
        
        if($oldProposal->exists()){
            return response()->json([
                'message' => 'there is already a proposal for this client offer' ,
            ]) ;
        }
        
        $data = $request->validated() ;
        $data['freelancer_id'] = auth('sanctum')->user()->role_id ;
        $clientOffer = ClientOffer::findOrFail($data['client_offer_id'])->first() ;
        $data['client_id'] = $clientOffer->client_id ;
        
        $proposal = ClientOfferProposal::create($data) ;
        
        $clientOffer->increment('proposals_count') ;

        return ClientOfferProposalResource::make($proposal) ;
    }

    /**
     * 
     * Freelancer Update Proposal Message
     * 
     * @param \App\Http\Requests\ClientOffer\UpdateClientOfferProposalRequest $request
     * @return ClientOfferProposalResource
     */
    public function updateProposal(UpdateClientOfferProposalRequest $request)
    {
        $data = $request->validated() ;

        $proposal = ClientOfferProposal::where('id' , $data['client_offer_proposal_id'])->first() ;

        $proposal->update(['message' => $data['message']]) ;

        return ClientOfferProposalResource::make($proposal) ;
    }
    /**
     * Freelancer Delete Proposal
     * 
     * @param \App\Models\ClientOfferProposal $clientOfferProposal
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function deleteProposal(ClientOfferProposal $clientOfferProposal)
    {
        if($clientOfferProposal->freelnacer_id != auth('sanctum')->user()->role_id)
        {
            return response()->json(['error'=>'this is not your proposal'],403) ;
        }

        ClientOffer::where('id' , $clientOfferProposal->client_offer_id)->first()->decrement('proposals_count') ;
        
        $clientOfferProposal->delete() ;
        
        return response()->json(['message' => 'deleted']) ;
    }
}
