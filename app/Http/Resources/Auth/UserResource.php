<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name ,
            'money' => $this->money , 
            'last_name' => $this->last_name ,
            'username' => $this->slug ,
            'email' => $this->email ,
            'role_id' => $this->role_id ,
            'role' => $this->RoleName ,
        ];
    }
}
