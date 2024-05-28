<?php

namespace App\Http\Resources\Utility;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            'url' => $this->url ,
            'public_id' => $this->public_id ,
            'size' => $this->size , 
            'type' => $this->type ,
            'extention' => $this->extention ,
        ];
    }
}
