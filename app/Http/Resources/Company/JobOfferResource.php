<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use App\Http\Resources\Category\SkillResource;
use App\Http\Resources\Category\JobRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobOfferResource extends JsonResource
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
            'description' => $this->description ,
            'status' => $this->status ,
            'location_type' => $this->location_type ,
            'attendence_type' => $this->attendence_type ,
            'max_sallary' => $this->max_sallary,
            'min_salary' => $this->min_salary , 
            'transportation' => $this->transportation ,
            'health_insurance' => $this->health_insurance ,
            'military_service' => $this->military_service ,
            'max_age' => $this->max_age ,
            'min_age' => $this->min_age ,
            'gender' => $this->gender ,
            'industry_name' => $this->industry_name ,
            'company' =>  MiniCompanyResource::make($this->whenLoaded('company')),
            'job_role' => JobRoleResource::make($this->whenLoaded('job_role')) ,
            'skills' => SkillResource::collection($this->whenLoaded('skills')) ,
            'military_service_required' => $this->military_service_required ,
            'gender_required' => $this->gender_required ,
            'age_required' => $this->age_required , 
            'proposals_count' => $this->proposals_count ,

        ];
    }
}
