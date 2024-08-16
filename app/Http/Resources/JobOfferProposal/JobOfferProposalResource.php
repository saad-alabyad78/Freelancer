<?php

namespace App\Http\Resources\JobOfferProposal;

use App\Http\Resources\Freelancer\FreelancerResource;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobOfferProposalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'freelancer_id' => $this->freelancer_id,
            'freelancer' => FreelancerResource::make($this->freelancer) ,
            'job_offer_id' => $this->job_offer_id,
            'rejected_at' => $this->rejected_at,
            'accepted_at' => $this->accepted_at,
            'message' => $this->message,
            'updated_at' => $this->updated_at,
            'created_at' => $this-> created_at,
        ];
    }
}
