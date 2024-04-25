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
            'usename' => $this->username ,
            'name' => $this->name ,
            'description' => $this->description ,
            'size' => $this->size , 
            'gallery_images' => GalleryImageResource::collection($this->gallery_images) ,
            'contact_links' => ContactLinkResource::collection($this->contact_links) ,
            'company_phones' => CompanyPhoneResource::collection($this->company_phones) ,
        ];
    }
}
