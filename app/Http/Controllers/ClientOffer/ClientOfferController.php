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

class ClientOfferController extends Controller
{
    public function filter(FilterClientOfferRequest $request)
    {
        $clientOffers = ClientOffer::filter($request->validated())
        ->with(['skills' , 'files' , 'sub_category'])
        ->orderByDesc('created_at')
        ->paginate(20) ;

        return ClientOfferResource::collection($clientOffers) ;
    }
    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(ClientOffer $clientOffer)
    {
        //todo only mine
        return ClientOfferResource::make($clientOffer->load([
            'files' ,
            'sub_category' ,
            'skills' ,
            ])) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientOfferRequest $request)
    {
        //todo only mine
        $data = $request->validated() ;
        
        $clientOffer = ClientOffer::findOrFail($data['client_offer_id']) ;

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
     * Remove the specified resource from storage.
     */
    public function destroy(ClientOffer $clientOffer)
    {
        //todo only mine
        $clientOffer->delete() ;

        return response()->json(['massage'=>'deleted']) ;
    }
}
