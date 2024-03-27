<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'gender' => $this->gender , 
            'avatar_image' => 
                $this->avatar_image ?? config('images.profile.avatar.'.$this->gender),
            'cover_image' => 
                $this->cover_image ?? config('images.profile.avatar.'.$this->gender),
        ];
    }
}
