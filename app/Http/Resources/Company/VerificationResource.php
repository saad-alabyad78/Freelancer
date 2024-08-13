<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use App\Http\Resources\Storage\FileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VerificationResource extends JsonResource
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
            'response' => $this->response , 
            'company_id' => $this->company_id , 
            'company' => $this->whenLoaded('company' , fn()=>CompanyResource::make($this->company)  , null), 
            'file' => $this->whenLoaded('file' , fn()=>FileResource::make($this->file)  , null), 
            'accepted_at' => $this->accepted_at , 
            'rejected_at' => $this->rejected_at , 
            'created_at' => $this->created_at , 
        ];
    }
}
