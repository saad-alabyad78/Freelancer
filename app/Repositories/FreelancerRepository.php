<?php

namespace App\Repositories;
use App\Models\Image;
use App\Models\Skill;
use App\Models\Skillable;
use App\Models\Freelancer;
use App\Interfaces\IBaseRepository;

class FreelancerRepository extends BaseRepository implements IBaseRepository
{
    public function create($data):Freelancer
    {
        $data['profile_image_url'] = Image::findOrFail($data['profile_image_id'])->first() ;
        $data['background_image_url'] = Image::findOrFail($data['background_image_id'])->first();
        
        $data['username'] = auth()->user()->slug ;

        $freelancer = Freelancer::create($data);

            $freelancer->user()->save(auth()->user()) ;

            $skillables = array_map(function($item) use ($freelancer){
                return [
                    'skill_id' => $item ,
                    'skillable_id' => $freelancer->id ,
                    'skillable_type' => Freelancer::class ,
                ] ;
            } , $data['skill_ids']);
            
            Skillable::insert($skillables) ;

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

        if(array_key_exists('skills' , $data))
        {
            $freelancer->skills()->detach() ;

            $skills = Skill::findMany($data['skill_ids']) ;

            $freelancer->skills()->saveMany($skills) ;
        }

        return $freelancer ;
    }
}
