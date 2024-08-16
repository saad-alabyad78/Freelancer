<?php

namespace App\Http\Controllers\ClientOffer;

use Mail;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Mail\MailMessage;
use App\Models\ClientOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Constants\ClientOfferStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientOffer\ClientOfferResource;
use App\Http\Requests\ClientOffer\FilterClientOfferForAdminRequest;
/**
 * @group Client Offer Management
 */
class ClientOfferAdminController extends Controller
{
    public function __construct(){
        $this->middleware('role:admin');
    }
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
     * Admin Accept
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
            'status' => ClientOfferStatus::ACTIVE ,
            'posted_at' => now()->toDateTimeString() ,
            ]) ;

        $user = User::where('role_type' , Client::class)
        ->where('role_id' , $clientOffer->client_id)->first() ;

        //Mail::to($user)->send(new MailMessage("تمت الموافقة على طلبك")) ;
        
        return ClientOfferResource::make($clientOffer) ;
    }
    /**
     * Admin Reject
     */
    public function reject(ClientOffer $clientOffer)
    {
        if($clientOffer->status != ClientOfferStatus::PENDING)
        {
            return response()->json([
                'message'=>'the offer status is not pending' ,
            ] , 422) ;
        }

        $user = User::where('role_type' , Client::class)
        ->where('role_id' , $clientOffer->client_id)->first() ;
        
        //Mail::to($user)->send(new MailMessage('لقد تم رفض عرض للاسف')) ;

        $clientOffer->delete() ;

        return response()->json(['message'=>'deleted']) ;
    }
    /**
     * Admin Delete
     */
    public function delete(ClientOffer $clientOffer)
    {
        $user = User::where('role_type' , Client::class)
        ->where('role_id' , $clientOffer->client_id)->first() ;

        //Mail::to($user)->send(new MailMessage('لقد تم رفض عرض للاسف')) ;
        
        //todo:send message to client

        $clientOffer->delete() ;

        return response()->json(['message' => 'deleted']) ;
    }
}
