<?php

namespace App\Http\Controllers\ClientOffer;

use App\Models\File;
use App\Models\ClientOffer;
use Illuminate\Http\Request;
use App\Constants\ClientOfferStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientOfferResource;
use App\Http\Requests\ClientOffer\CreateClientOfferRequest;
use App\Http\Requests\ClientOffer\FilterClientOfferRequest;
use App\Http\Requests\ClientOffer\UpdateClientOfferRequest;
/**
 * @group Client Offer Managment
 * 
 */
class ClientOfferController extends Controller
{
    public function __construct(){
        $this->middleware('role:client');
    }
    /**
     *  Client-Filter List Client Offers
     * @param \App\Http\Requests\ClientOffer\FilterClientOfferRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function clientFilter(FilterClientOfferRequest $request)
    {
        $clientOffers = ClientOffer::filter($request->validated())
        ->where('client_id' , $this->user->role_id)
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
        $data['client_id'] = $this->user->role_id ;
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