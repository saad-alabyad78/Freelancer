<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use App\Http\Resources\MilestoneResource;
use App\Http\Resources\Storage\FileResource;
use App\Http\Resources\Client\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Freelancer\FreelancerResource;

class ProjectResource extends JsonResource
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
            'freelancer_id' => $this->whenLoaded('freelancer' , fn()=>FreelancerResource::make($this->freelancer) , null), 
            'client_id' => $this->whenLoaded('client' , fn()=>ClientResource::make($this->client) , null),
            'price' => $this->price ,
            'client_money' => $this->client_money ,
            'days' => $this->days ,
            'milestones' => $this->whenLoaded('milestones' , fn()=>MilestoneResource::collection($this->milestones) , null) ,
            'files' => $this->whenLoaded('files' , fn()=>FileResource::collection($this->files) , null),
            'finished_at' => $this->finished_at ,
            'created_at' => $this->created_at ,
            'updated_at' => $this->updated_at ,
        ];
    }
}
