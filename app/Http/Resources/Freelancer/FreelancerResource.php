<?php

namespace App\Http\Resources\Freelancer;

use Illuminate\Http\Request;
use App\Http\Resources\Category\SkillResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\Job_RoleResource;

class FreelancerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ,
            'profile_image_url' =>  $this->profile_image_url ,
            'background_image_url' =>  $this->background_image_url ,
            'username' => $this->username ,
            'headline' => $this->headline ,
            'description' => $this->description ,
            'city' => $this->city , 
            'date_of_birth' => $this->date_of_birth ,
            'gender' => $this->gender ,
            'job_role_id' => Job_RoleResource::make($this->whenLoaded('job_role'))  ,
            'skills' => SkillResource::collection($this->whenLoaded('skills')) ,
            'portfolios' => PortfolioResource::collection($this->whenLoaded('portfolios'))
        ];
    }
}
