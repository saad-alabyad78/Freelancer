<?php

namespace App\Repositories;
use App\Models\Image;
use App\Models\Company;
use App\Models\ContactLink;
use App\Models\CompanyPhone;
use App\Interfaces\ICompanyRepository;

class CompanyRepository extends BaseRepository implements ICompanyRepository
{
    public function create($data):Company
    {
        if($data['profile_image_id'] ?? false)
            $data['profile_image_url'] = Image::findOrFail($data['profile_image_id'])->first() ;
        if($data['background_image_url'] ?? false)
            $data['background_image_url'] = Image::findOrFail($data['background_image_id'])->first();
    
        $data['username'] = auth()->user()->slug ;
        
        $company = Company::create($data);

            $company->user()->save(auth()->user()) ;

            //create links 

            if(array_key_exists('contact_links' , $data))
            {
                $contact_links = [] ;
            
                foreach($data['contact_links'] as $link_name)
                {
                    $contact_links[] = new ContactLink(['name' => $link_name]) ;
                }
                $company->contact_links()->saveMany($contact_links) ;
            }

            //store and create images

            if(array_key_exists('gallery_images_ids' , $data))
            {
                //todo:chnuk set in the database                 

                $gallery_images = Image::whereIn('id' , $data['gallery_images_ids'])->get() ;
                
                $company->gallery_images()->saveMany($gallery_images) ;
            }

            //create phones 

            if(array_key_exists('company_phones' , $data))
            {
                $company_phones = [] ;
            
                foreach($data['company_phones'] as $company_phone)
                {
                    $company_phones[] = new CompanyPhone(['number' => $company_phone]) ;
                }
                $company->company_phones()->saveMany($company_phones) ;

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
            $data['profile_image_url'] = Image::findOrFail($data['profile_image_id'])->first() ;
        }
        if($data['background_image_id'] ?? false and $company?->background_image_id ?? false)
        {
            Image::where('id' , $company->background_image_id)->update([
                'deleted' => true ,
                'imagable_id' => $company->id ,
                'imagable_type' => company::class ,
            ]);
            $data['background_image_url'] = Image::findOrFail($data['profile_image_id'])->first() ;
        }

        $company->update($data) ;

        //update gallery images
        if(array_key_exists('gallery_images' , $data))
        {
            $idsArray = array_map( function($item){
                return $item['id'];
            }, $data['gallery_images']) ;

            $company->gallery_images()
            ->whereNotIn('id' , $idsArray)->update([
                'imagable_id' => null ,
                'imagable_type' => null ,
                'deleted' => true 
            ]) ;

            Image::whereIn('id' , $idsArray)
                    ->whereNull('imagable_id')
                    ->whereNull('imagable_type')
                    ->update([
                    'imagable_id' => $company->id ,
                    'imagable_type' => Company::class ,
                    ]) ;
        }
        
        
        //update contact links
        if(array_key_exists('contact_links' , $data))
        {
            $company->contact_links()->whereNotIn('name' , $data['contact_links'])->delete() ;
            $names = $company->contact_links()->pluck('name')->toArray() ;
            $contact_links = [] ;
        
            foreach($data['contact_links'] as $link_name)
            {
                if(!in_array($link_name , $names))
                $contact_links[] = new ContactLink(['name' => $link_name]) ;
            }
            $company->contact_links()->saveMany($contact_links) ;
        }

        //update company phones 
        if(array_key_exists('company_phones' , $data))
        {
            $company->company_phones()->whereNotIn('number' , $data['company_phones'])->delete() ;
            $numbers = $company->company_phones()->pluck('number')->toArray() ;
            $company_phones = [] ;
        
            foreach($data['company_phones'] as $company_phone)
            {
                if(!in_array($company_phone , $numbers))
                $company_phones[] = new CompanyPhone(['number' => $company_phone]) ;
            }
            $company->company_phones()->saveMany($company_phones) ;
        }

        return $company ;
    }
}