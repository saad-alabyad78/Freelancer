<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use App\Http\Resources\Auth\UserResource;
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
            'profile_image_id' => $this->profile_image_id , 
            'background_image_id' => $this->background_image_id ,
            'profile_image_url' => $this->profile_image_url , 
            'background_image_url' => $this->background_image_url ,
            'user' => $this->whenLoaded('user' , fn()=>UserResource::make($this->user) , null) ,
            'created_at' => $this->created_at ,
            'updated_at' => $this->updated_at ,
        ];
    }
}
