<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Storage\FileResource;
use App\Http\Resources\Storage\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Freelancer\FreelancerResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data =  [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price , 
            'mine_as_client' => null ,
            'image' => $this->whenLoaded('image' , fn()=>ImageResource::make($this->image),fn()=>ImageResource::make($this->image)) ,
            'clients_count' => $this->clients_count ?? 0 ,
            'freelancer_id' => $this->whenLoaded('freelancer' , fn()=>FreelancerResource::make($this->freelancer) , null),
            'images' =>  $this->whenLoaded('images' , fn()=>ImageResource::collection($this->images) , null),
            'files' => $this->whenLoaded('files' , fn()=>FileResource::collection($this->files) , null),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];

        if(auth('sanctum')->check())
        {
            $user = User::where('id' , auth('sanctum')->id())->first() ;

            if($user->role_type == Client::class)
            {
                $data['mine_as_client'] = DB::table('client_product')
                ->where('client_id' , $user->role_id)
                ->where('product_id' , $this->id)
                ->exists() ;
            }
        }

        return $data ;
    }
}
