<?php

namespace App\Repositories;
use App\Models\Image;
use App\Models\Skill;
use App\Models\Skillable;
use App\Models\Freelancer;
use App\Interfaces\IBaseRepository;
use App\Interfaces\IFreelancerRepository;

class FreelancerRepository extends BaseRepository implements IFreelancerRepository
{
    public function create($data):Freelancer
    {
        if(isset($data['profile_image_id']))
            $data['profile_image_url'] = Image::findOrFail($data['profile_image_id'])->first() ;
        if(isset($data['background_image_id']))
            $data['background_image_url'] = Image::findOrFail($data['background_image_id'])->first();
    
        
        $data['username'] = auth('sanctum')->user()->slug ;

        $freelancer = Freelancer::create($data);

        $freelancer->user()->save(auth('sanctum')->user()) ;

        $freelancer->skills()->attach($data['skill_ids']) ;

        return $freelancer ;
    }
    public function update(Freelancer $freelancer , $data):Freelancer
    {
        if($data['profile_image_id'] ?? false and $freelancer?->profile_image_id ?? false)
        {
            Image::where('id' , $freelancer->profile_image_id)->update([
                'deleted' => true ,
                'imagable_id' => $freelancer->id ,
                'imagable_type' => freelancer::class ,
            ]);
            $data['profile_image_url'] = Image::findOrFail($data['profile_image_id'])->first() ;
        }
        if($data['background_image_id'] ?? false and $freelancer?->background_image_id ?? false)
        {
            Image::where('id' , $freelancer->background_image_id)->update([
                'deleted' => true ,
                'imagable_id' => $freelancer->id ,
                'imagable_type' => freelancer::class ,
            ]);
            $data['background_image_url'] = Image::findOrFail($data['profile_image_id'])->first() ;
        }

        $freelancer->update($data) ;

        if(array_key_exists('skill_ids' , $data))
        {
            Skillable::where('skillable_id' , $freelancer->id)
                ->whereNotIn('skill_id' , $data['skill_ids'])
                ->delete() ;
                
            $skillables = array_map(function($id) use ($freelancer){
                return [
                    'skill_id' => $id , 
                    'skillable_id' => $freelancer->id ,
                    'skillable_type' => Freelancer::class ,
                ];
            } , $data['skill_ids']) ;

            Skillable::upsert($skillables , 'id') ;                      
        }
        return $freelancer ;
    }
}
