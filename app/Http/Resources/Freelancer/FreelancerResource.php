<?php

namespace App\Http\Resources\Freelancer;

use App\Models\Rate;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Category\SkillResource;
use App\Http\Resources\Category\JobRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'rating' => $this->rating ?? Rate::where('model_id' , $this->id)
            ->where('model_type' , Freelancer::class)
            ->average('number') ,

            'id' => $this->id ,
            'profile_image_url' =>  $this->profile_image_url ,
            'background_image_url' =>  $this->background_image_url ,
            'username' => $this->username ,
            'headline' => $this->headline ,
            'description' => $this->description ,
            'city' => $this->city , 
            'date_of_birth' => $this->date_of_birth ,
            'age' => $this->age ,
            'gender' => $this->gender ,
            'user' => $this->whenLoaded('user' , fn()=>UserResource::make($this->user) , null) ,
            'job_role' => $this->whenLoaded('job_role' , fn()=>JobRoleResource::make($this->job_role) , null) ,
            'skills' => $this->whenLoaded('skills' , fn()=>SkillResource::collection($this->skills) , null) ,
            'portfolios' => $this->whenLoaded('portfolios' , fn()=>PortfolioResource::collection($this->portfolios) , null) ,
        ];
    }
}
