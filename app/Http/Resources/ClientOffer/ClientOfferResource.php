<?php

namespace App\Http\Resources\ClientOffer;

use App\Models\User;
use App\Models\Client;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use Psy\Command\WhereamiCommand;
use App\Models\ClientOfferProposal;
use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Storage\FileResource;
use App\Http\Resources\Client\ClientResource;
use App\Http\Resources\Category\SkillResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\SubCategoryResource;

class ClientOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::where('id' , auth('sanctum')->id())->first() ;
        
        $i_proposed = null ;
        
        if($user->role_type == Freelancer::class)
        {
            $i_proposed = ClientOfferProposal::
                where('freelancer_id' , $user->role_id)
                ->Where('client_offer_id' , $this->id)
                ->exists() ;
        }
        

        return [
            'status' => $this->status,
            'id' => $this->id ,
            'client_id' => $this->client_id,
            'sub_category' => SubCategoryResource::make($this->whenLoaded('sub_category')),
            'title' => $this->title,
            'description' => $this->description,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'days' => $this->days,
            'proposals_count' => $this->proposals_count ,
            'skills' => SkillResource::collection($this->whenLoaded('skills')) ,
            'files' => FileResource::collection($this->whenLoaded('files')) ,
            'client' => ClientResource::make($this->client) ,
            'i_proposed' => $i_proposed ,
            'posted_at' => $this->posted_at ,
            'created_at' => $this->created_at ,
            'updated_at' => $this->updated_at ,
        ];
    }
}
