<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyMiniResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'profile_image' => $this->profile_image , 
            'profile_image_url' => $this->profile_image_url , 
            'username' => $this->username ,
            'name' => $this->name ,
            'size' => $this->size , 
            'industry_name' => $this->industry_name ,
        ];
    }
}
