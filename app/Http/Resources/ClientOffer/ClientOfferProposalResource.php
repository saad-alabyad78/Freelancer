<?php

namespace App\Http\Resources\ClientOffer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Freelancer\FreelancerResource;

class ClientOfferProposalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id ,
            'freelancer_id' => $this->freelancer_id ,
            'freelancer' => FreelancerResource::make($this->freelancer) ,
            'client_id'=> $this->client_id ,
            'client_offer_id'=> $this->client_offer_id ,
            'message'=> $this->message ,
            'days'=> $this->days ,
            'price'=> $this->price ,
            'accepted_at'=> $this->accepted_at ,
            'rejected_at'=> $this->rejected_at ,
            'created_at'=> $this->created_at ,
            'updated_at'=> $this->updated_at ,
        ];
    }
}
