<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use App\Http\Resources\Storage\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'street_address' => $this->street_address,
            'lat' => $this->lat ,
            'lon' => $this->lon ,
            'city' => $this->city ,
            'region' => $this->region ,
            'id' => $this->id,
            'profile_image_url' => $this->profile_image_url , 
            'background_image_url' => $this->background_image_url ,
            'profile_image_id' => $this->profile_image_id , 
            'background_image_id' => $this->background_image_id ,
            'verified_at' => $this->verified_at ,
            'username' => $this->username ,
            'name' => $this->name ,
            'description' => $this->description ,
            'size' => $this->size , 
            'industry_name' => $this->industry_name ,
            'gallery_images' => ImageResource::collection($this->whenLoaded('gallery_images')) ,
            ];
    }
}
