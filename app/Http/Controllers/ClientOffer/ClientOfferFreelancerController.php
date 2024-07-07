<?php

namespace App\Http\Controllers\ClientOffer;

use App\Models\ClientOffer;
use Illuminate\Http\Request;
use App\Models\ClientOfferProposal;
use App\Constants\ClientOfferStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientOfferResource;
use App\Http\Resources\ClientOffer\ClientOfferProposalResource;
use App\Http\Requests\ClientOffer\CreateClientOfferProposalRequest;
use App\Http\Requests\ClientOffer\UpdateClientOfferProposalRequest;
use App\Http\Requests\ClientOffer\FilterClientOfferForFreelancerRequest;

class ClientOfferFreelancerController extends Controller
{
    public function freelancerFilter(FilterClientOfferForFreelancerRequest $request)
    {
        $clientOffers = ClientOffer::filter($request->validated())
        ->with(['skills' /*, 'files'*/ , 'sub_category'])
        ->orderByDesc('created_at')
        ->paginate(20) ;

        return ClientOfferResource::collection($clientOffers) ;
    }
    public function showClientOffer(ClientOffer $clientOffer)
    {
        if($clientOffer->status == ClientOfferStatus::PENDING)
        {
            return response()->json(['error' => 'you can\'t see pending offers'],403) ;
        }
        return ClientOfferResource::make($clientOffer) ;
    }
    public function createProposal(CreateClientOfferProposalRequest $request)
    {
        $data = $request->validated() ;
        $data['freelancer_id'] = $this->user->role_id ;
        $data['client_id'] = ClientOffer::findOrFail($data['client_offer_id'])->first()->client_id ;
        
        $proposal = ClientOfferProposal::create($data) ;

        return ClientOfferProposalResource::make($proposal) ;
    }
    public function updateProposal(UpdateClientOfferProposalRequest $request)
    {
        $data = $request->validated() ;

        $proposal = ClientOfferProposal::findOrFail($data['client_offer_proposal_id'])->first() ;

        $proposal->update(['message' => $data['message']]) ;

        return ClientOfferProposal::make($proposal) ;
    }
    public function deleteProposal(ClientOfferProposal $clientOfferProposal)
    {
        if($clientOfferProposal->freelnacer_id != $this->user->role_id)
        {
            return response()->json(['error'=>'this is not your proposal'],403) ;
        }
        $clientOfferProposal->delete() ;
        
        return response()->json(['message' => 'deleted']) ;
    }
}
