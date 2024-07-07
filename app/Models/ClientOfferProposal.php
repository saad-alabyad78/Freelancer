<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientOfferProposal extends Model
{
    use HasFactory;

    protected $fillabe =
    [
        'id' ,
        'freelancer_id' ,
        'client_id' ,
        'client_offer_id' ,
        'message' ,
        'accepted_at' ,
        'rejected_at' ,
        'created_at' ,
        'updated_at' ,
    ] ;

    public function freelnacer():BelongsTo
    {
        return $this->belongsTo(Freelancer::class) ;
    }
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class) ;
    }
    public function client_offer():BelongsTo
    {
        return $this->belongsTo(ClientOffer::class) ;
    }
}
