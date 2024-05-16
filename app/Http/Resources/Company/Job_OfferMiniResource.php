<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use App\Http\Resources\Category\SkillResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\Job_RoleResource;

class Job_OfferMiniResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ,
            'description' => $this->description ,
            'status' => $this->status ,
            'type' => $this->type ,
            'max_sallary' => $this->max_sallary,
            'min_salary' => $this->min_salary , 
            'transportation' => $this->transportation ,
            'health_insurance' => $this->health_insurance ,
            'military_service' => $this->military_service ,
            'max_age' => $this->max_age ,
            'min_age' => $this->min_age ,
            'gender' => $this->gender ,
            'industry_name' => $this->industry_name ,
            'company' =>  MiniCompanyResource::make($this->company),
            'job_role' => Job_RoleResource::make($this->job_role) ,
            'skills' => SkillResource::collection($this->skills) ,
        ];
    }
}
