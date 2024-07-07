<?php

namespace App\Http\Controllers\ClientOffer;

use Carbon\Carbon;
use App\Models\ClientOffer;
use Illuminate\Http\Request;
use App\Constants\ClientOfferStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientOfferResource;
use App\Http\Requests\ClientOffer\FilterClientOfferForAdminRequest;

class ClientOfferAdminController extends Controller
{
    /**
     * Admin-Filter List Client Offers
     */
    public function adminFilter(FilterClientOfferForAdminRequest $request)
    {
        $clientOffers = ClientOffer::filter($request->validated())
        ->with(['skills' , 'files' , 'sub_category'])
        ->orderByDesc('created_at')
        ->paginate(20) ;

        return ClientOfferResource::collection($clientOffers) ;
    }
    /**
     * Admin
     */
    public function accept(ClientOffer $clientOffer)
    {
        if($clientOffer->status != ClientOfferStatus::PENDING)
        {
            return response()->json([
                'message'=>'the offer status is not pending' ,
            ] , 422);
        }

        $clientOffer->update([
            'status' => ClientOfferStatus::AVTIVE ,
            'posted_at' => Carbon::now() ,
            ]) ;

        //todo:send message to client
        
        return ClientOfferResource::make($clientOffer) ;
    }
    /**
     * Reject
     */
    public function reject(ClientOffer $clientOffer)
    {
        if($clientOffer->status != ClientOfferStatus::PENDING)
        {
            return response()->json([
                'message'=>'the offer status is not pending' ,
            ] , 422) ;
        }

        //todo:send message to client

        $clientOffer->delete() ;

        return response()->json(['message'=>'deleted']) ;
    }
    /**
     * Delete
     */
    public function delete(ClientOffer $clientOffer)
    {
        //todo:send message to client

        $clientOffer->delete() ;

        return response()->json(['message' => 'deleted']) ;
    }
}
