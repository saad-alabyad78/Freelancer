<?php

namespace App\Http\Resources\Freelancer;

use App\Models\Like;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Storage\FileResource;
use App\Http\Resources\Storage\ImageResource;
use App\Http\Resources\Category\SkillResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        var_dump(Auth::check()) ;
        return [
            'id' => $this->id ,
            'title' => $this->title ,
            'section' => $this->section ,
            'views_count' => $this->views_count ,
            'likes_count' => $this->likes_count ,
            'url' => $this->url , 

            'date' => $this->date , 
            
            // 'liked_by_me' => Auth::check() ? Like::where([
            //             'user_id' => Auth::id() ,
            //             'likable_id' => $this->id ,
            //             'likable_type' => Portfolio::class , 
            //         ])->exists() : false,

            'skills' => SkillResource::collection($this->whenLoaded('skills')) ,
            'files' => FileResource::collection($this->whenLoaded('files')) ,
            'images' => ImageResource::collection($this->whenLoaded('images')) ,
            'updated_at' => $this->updated_at , 
            'created_at' => $this->created_at ,

            
        ];
    }
}
