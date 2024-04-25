<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



class imageService
{
    public static function store_image($image , $storage_path = 'public')
    {
        //default accessor in the model
        if(!$image)return null ;

        $image_name = Str::random(32 , ) . '.' .
        $image->getClientOriginalExtension() ;

         Storage::disk($storage_path)
         ->put($image_name , file_get_contents($image));

         return $image_name ;
    } 
}