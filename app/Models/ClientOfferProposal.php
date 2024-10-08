<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientOfferProposal extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'id' ,
        'freelancer_id' ,
        'client_id' ,
        'client_offer_id' ,
        'message' ,
        'days' ,
        'price' ,
        'accepted_at' ,
        'rejected_at' ,
        'created_at' ,
        'updated_at' ,
    ] ;

    protected $casts = [
        'price' => 'integer' , 
        'days' => 'integer' ,
    ] ;

    public function freelancer():BelongsTo
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
