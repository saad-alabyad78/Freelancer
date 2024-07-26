<?php

namespace App\Repositories;
use App\Models\Image;
use App\Models\Client;
use App\Interfaces\IClientRepository;

class ClientRepository extends BaseRepository implements IClientRepository{
    public function update(Client $client , $data):Client
    {
        if(array_key_exists('profile_image_id' , $data) and $client?->profile_image_id ?? false)
        {
            Image::where('id' , $client->profile_image_id)->update([
                'deleted' => true ,
                'imagable_id' => $client->id ,
                'imagable_type' => Client::class ,
            ]);
            if($data['profile_image_url'] != null)
            $data['profile_image_url'] = Image::findOrFail($data['profile_image_id'])->pluck('url')->first() ;
        }
        if(array_key_exists('background_image_id' , $data) and $client?->background_image_id ?? false)
        {
            Image::where('id' , $client->background_image_id)->update([
                'deleted' => true ,
                'imagable_id' => $client->id ,
                'imagable_type' => Client::class ,
            ]);
            if($data['background_image_url'] != null)
            $data['background_image_url'] = Image::findOrFail($data['profile_image_id'])->pluck('url')->first() ;
        }

        $client->update($data);

        return $client ;
    }
}