<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MiniCompanyResource extends JsonResource
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
            'background_image' => $this->background_iamge ,
            'username' => $this->username ,
            'name' => $this->name ,
        ];
    }
}
