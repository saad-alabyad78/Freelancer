<?php

namespace App\Http\Controllers\ClientOffer;

use App\Models\File;
use App\Models\Pill;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Freelancer;
use App\Models\ClientOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ClientOfferProposal;
use App\Constants\ClientOfferStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\PillResource;
use App\Http\Resources\Project\ProjectResource;
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
        //DB::beginTransaction();
        $proposal = ClientOfferProposal::where('id' , $request->input('proposal_id'))
        ->first() ;

        $user = User::where('id' , auth('sanctum')->id())->first();
        if($user->money < $proposal->price){
            return response()->json([
                'message' => 'you dont have money' ,
                'client money' => $user->money ,
                'proposal price' => $proposal->price ,
            ]) ;
        }

        $proposal->update(['accepted_at' => now()->toDateTimeString()]) ;

        ClientOfferProposal::where('client_offer_id' , $proposal->client_offer_id)
        ->whereNot('id' , $proposal->id)
        ->update(['rejected_at' => now()->toDateTimeString()]) ;

        $offer = ClientOffer::where('id' , $proposal->client_offer_id)->first() ;
        $offer->update([
            'status' => ClientOfferStatus::IN_PROGRESS , 
            'freelancer_id' => $proposal->freelancer_id ,
        ]) ;

        $project = Project::create([
            'freelancer_id' =>$offer->freelancer_id, 
            'client_id' => $offer->client_id,
            'finished_at' => null,
            'price' => $proposal->price ,
            'days' => $proposal->days ,
            'client_money' => $user->money,
            'client_ok' => false ,
            'freelancer_ok' => false ,
        ]) ;
        
        $user->decrement('money' , $proposal->price) ;
        
        $pill = Pill::create([
            'from_id' => $offer->client_id ,
            'from_type' => Client::class,
            'to_id' => $project->id ,
            'to_type' => Project::class ,
            'description' => 'this pill is to pay for the project building ',
            'price' => $proposal->price ,  
        ]) ;

        //todo send notification to the freelancer 
        
        return response()->json(
        [
            'pill' => PillResource::make($pill->load([
                'from' , 
                'to' ,
            ])) ,
            'project' => ProjectResource::make($project->load([
                'freelancer' , 
                'client' ,
            ])) ,
            'client_offer' => ClientOfferResource::make($offer->load([
                'freelancer',
                'client',
                'sub_category',
                'files',
                'skills',
            ])),
        ]);
    }
    //todo test 
    /**
     * Reject Proposals
     * @param \App\Http\Requests\ClientOffer\ClientRejectProposalsRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function rejectProposals(ClientRejectProposalsRequest $request)
    {
        ClientOfferProposal::whereIn('id' , $request->input('proposal_ids'))
        ->update(['rejected_at' => now()->toDateTimeString()]) ;

        $proposals =  ClientOfferProposal::whereIn('id' , $request->input('proposal_ids'))->get();

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
        ->whereNull(['rejected_at' , 'accepted_at'])
        ->orderBy('days' , $request->boolean('orderByDays')? 'asc' : 'desc')
        ->orderBy('price' , $request->boolean('orderByPrice')? 'asc' : 'desc')
        ->paginate() ;

        return ClientOfferProposalResource::collection(
            $proposals->load(['freelancer.job_role' , 'freelancer.skills'])
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

        if(!$clientOffer->freelancer_id)
        {
            return ClientOfferResource::make($clientOffer->load([
            'files' ,
            'sub_category' ,
            'skills' ,
            ])) ;
        }

        $project = Project::where('client_offer_id' , $clientOffer->id)
        ->with(['freelancer' , 'client'])
        ->first() ;

        $pill = Pill::where([
            'from_id' => $clientOffer->client_id ,
            'from_type' => Client::class,
            'to_id' => $project->id ,
            'to_type' => Project::class ,
        ])
        ->with(['from' , 'to'])
        ->first() ;
        
        return response()->json(
            [
                'pill' => PillResource::make($pill),
                
                'project' => ProjectResource::make($project),
                
                'client_offer' => ClientOfferResource::make($clientOffer->load([
                    'freelancer',
                    'client',
                    'sub_category',
                    'files',
                    'skills',
                ])),
            ]);
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