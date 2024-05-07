<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\Job_RoleResource;

class Job_OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
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
            'company_id' =>  MiniCompanyResource::make($this->company),
            'job_role_id' => Job_RoleResource::make($this->job_role) ,
        ];
    }
}
