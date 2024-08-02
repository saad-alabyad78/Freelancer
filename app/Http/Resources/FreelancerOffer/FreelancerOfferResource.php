<?php

namespace App\Http\Resources\FreelancerOffer;

use App\Http\Resources\Auth\UserResource;
use App\Models\User;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use App\Http\Resources\Storage\FileResource;
use App\Http\Resources\Category\SkillResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\SubCategoryResource;
use App\Http\Resources\Freelancer\FreelancerResource;

class FreelancerOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::where('role_id' , $this->freelancer_id)
                ->where('role_type' , Freelancer::class)->first() ;
                
        return [
            'id' => $this->id ,
            'freelancer_id' => $this->freelancer_id,
            'freelancer' => FreelancerResource::make($this->freelancer) ,
            'user' => UserResource::make($user) ,
            'sub_category' => SubCategoryResource::make($this->whenLoaded('sub_category')),
            'title' => $this->title,
            'status' => $this->status,
            'description' => $this->description,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'days' => $this->days,
            'proposals_count' => $this->proposals_count ,
            'skills' => SkillResource::collection($this->whenLoaded('skills')) ,
            'files' => FileResource::collection($this->whenLoaded('files')) ,
            'posted_at' => $this->posted_at ,
            'created_at' => $this->created_at ,
            'updated_at' => $this->updated_at ,
        ];
    }
}
