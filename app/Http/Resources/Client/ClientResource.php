<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'username' => $this->username ,
            'gender' => $this->gender ,
            'city' => $this->city ,
            'date_of_birth' => $this->date_of_birth ,
            'profile_image_url' => $this->profile_image_url , 
            'background_image_url' => $this->background_image_url ,
        ];
    }
}
