<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\FileNotFonudException;



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

    public static function get_image($disk , $image) 
    {
        static::validate($disk , $image) ;

        $file = Storage::disk($disk)->get($image) ;
        $type = Storage::disk($disk)->mimeType($image) ;

        
        return $file ;
    } 

    public static function get_type($disk , $image) 
    {
        static::validate($disk , $image) ;

        $type = Storage::disk($disk)->mimeType($image) ;

        return $type ;
    } 

    public static function delete($disk , $image)
    {
        static::validate($disk , $image);

        Storage::disk($disk)->delete($image) ;
    }

    private static function validate($disk , $image) : void
    {
        if(!array_key_exists($disk , config('filesystems.disks'))){
            throw new FileNotFonudException('disk ' . $disk . ' not found' , 404 ) ;
        }
        if(!Storage::disk($disk)->exists($image)){
            throw new FileNotFonudException('image ' . $image . ' not found' , 404 ) ;
        }
    }
    
}