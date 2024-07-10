<?php

namespace App\Repositories;
use App\Models\Image;
use App\Models\Company;
use App\Interfaces\ICompanyRepository;

class CompanyRepository extends BaseRepository implements ICompanyRepository
{
    public function create($data):Company
    {
        if(array_key_exists('profile_image_id',$data) and !is_null($data['profile_image_id']))
            $data['profile_image_url'] = Image::findOrFail($data['profile_image_id'])->first()->url ;
        if(array_key_exists('background_image_id',$data) and !is_null($data['background_image_id']))
            $data['background_image_url'] = Image::findOrFail($data['background_image_id'])->first()->url;
    
        $data['username'] = auth()->user()->slug ;
        
        $company = Company::create($data);
        
        $company->user()->save(auth()->user()) ;



        //store and create images

        if(array_key_exists('gallery_image_ids' , $data))
        {
            //todo:chnuk set in the database                 

            $gallery_images = Image::whereIn('id' , $data['gallery_image_ids'])->get() ;
            
            $company->gallery_images()->saveMany($gallery_images) ;
        }
        

        return $company ;
    }
    public function update(Company $company , $data):Company
    {
        if($data['profile_image_id'] ?? false and $company?->profile_image_id ?? false)
        {
            Image::where('id' , $company->profile_image_id)->update([
                'deleted' => true ,
                'imagable_id' => $company->id ,
                'imagable_type' => company::class ,
            ]);
            $data['profile_image_url'] = Image::findOrFail($data['profile_image_id'])->first()->url ;
        }
        if($data['background_image_id'] ?? false and $company?->background_image_id ?? false)
        {
            Image::where('id' , $company->background_image_id)->update([
                'deleted' => true ,
                'imagable_id' => $company->id ,
                'imagable_type' => company::class ,
            ]);
            $data['background_image_url'] = Image::findOrFail($data['profile_image_id'])->first()->url ;
        }

        $company->update($data) ;

        //update gallery images
        if(array_key_exists('gallery_image_ids' , $data))
        {
            $idsArray = array_map( function($item){
                return $item['id'];
            }, $data['gallery_image_ids']) ;

            $company->gallery_images()
            ->whereNotIn('id' , $idsArray)->update([
                'imagable_id' => null ,
                'imagable_type' => null ,
                'deleted' => true 
            ]) ;

            Image::whereIn('id' , $idsArray)
                    ->whereNull('imagable_id')
                    ->whereNull('imagable_type')
                    ->whereNot('deleted')
                    ->update([
                    'imagable_id' => $company->id ,
                    'imagable_type' => Company::class ,
                    ]) ;
        }
        
        return $company ;
    }
}