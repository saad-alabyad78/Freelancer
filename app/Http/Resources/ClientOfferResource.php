<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\Storage\FileResource;
use App\Http\Resources\Category\SkillResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\SubCategoryResource;

class ClientOfferResource extends JsonResource
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
            'client_id' => $this->client_id,
            'sub_category' => SubCategoryResource::make($this->whenLoaded('sub_category')),
            'title' => $this->title,
            'status' => $this->status,
            'description' => $this->description,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'days' => $this->days,
            'skills' => SkillResource::collection($this->whenLoaded('skills')) ,
            'files' => FileResource::collection($this->whenLoaded('files')) ,
            'posted_at' => $this->posted_at ,
            'created_at' => $this->created_at ,
            'updated_at' => $this->updated_at ,
        ];
    }
}
