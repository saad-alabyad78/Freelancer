<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
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
            'profile_image' => $this->profile_image , 
            'background_image' => $this->background_image ,
            'profile_image_url' => $this->profile_image_url , 
            'background_image_url' => $this->background_image_url ,
            
            'username' => $this->username ,
            'name' => $this->name ,
            'description' => $this->description ,
            'size' => $this->size , 
            'industry_name' => $this->industry_name ,
            'gallery_images' => GalleryImageResource::collection($this->gallery_images) ,
            'contact_links' => ContactLinkResource::collection($this->contact_links) ,
            'company_phones' => CompanyPhoneResource::collection($this->company_phones) ,
        ];
    }
}
